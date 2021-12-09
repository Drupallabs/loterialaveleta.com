<?php

namespace Drupal\mi_monedero\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\mi_monedero\Entity\MonederoInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Drupal\mi_monedero\RedsysAPI as RedsysAPI;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class MonederoController.
 *
 *  Returns responses for Monedero routes.
 */
class MonederoController extends ControllerBase implements ContainerInjectionInterface
{

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * The config factory
   *
   * @var Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $factory;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    $instance->configFactory = $container->get('config.factory');
    return $instance;
  }

  public function monederoUser($user)
  {
    $config = $this->configFactory->get('mi_monedero.configuracion');
    $monedero = $this->entityTypeManager()->getStorage('monedero');
    $userid = $user->get('uid')->value;
    $mo = reset($monedero->loadByProperties(['user_id' => $userid]));
    if ($mo) { // Tiene monedero este usuario
      $euros = $mo->get('cantidad')->value;
    } else {
      $euros = 0;
    }

    return array(
      '#theme' => 'monedero_user',
      '#monedero' => $euros,
      '#user' => $user,
      '#activate_tpv' => $config->get('activate_tpv')
    );
  }
  public function depositarBackOkNotificacion(Request $request)
  {
    $config = $this->configFactory->get('mi_monedero.configuracion');
    $red = new RedsysAPI;
    if ($request->get('Ds_MerchantParameters')) {
      $version = $request->get('Ds_SignatureVersion');
      $params = $request->get('Ds_MerchantParameters');
      $signatureRecibida = $request->get('Ds_Signature');
      $signature = $config->get('signature');
      $red->decodeMerchantParameters($params);
      $codigoRespuesta = $red->getParameter("Ds_Response");
      $ds_order = $red->getParameter("Ds_Order");

      $signatureCalc = $red->createMerchantSignatureNotif($signature, $params);
      if ($signatureCalc === $signatureRecibida) {
        if ($codigoRespuesta == '0000') {
          
          $split = explode("-", $ds_order);
          $userid = (int)$split[0];
          $total = $red->getParameter('Ds_Amount');
          if ($userid) {
            $usere = $this->entityTypeManager()->getStorage('user');
            $user = reset($usere->loadByProperties(['uid' => $userid]));
            $price = (float) strval($total / 100);
            if ($user) {
              \Drupal::service('mi_monedero.monedero_manager')->masMonedero($user, $price);
            }
            \Drupal::logger('mi_monedero')->error('Ds_Response' . $codigoRespuesta . 'Total: ' . $price . 'User:' . $user->id());
          } else {
            \Drupal::logger('mi_monedero')->error('No id current' . 'Iserid: ' . $userid . 'Current:' . $this->currentUser()->id());
          }
        }
      } else {
        \Drupal::logger('mi_monedero')->error(print_r($params, true));
      }
      return new Response('');
    } else return new Response('');
  }

  public function depositarBackOk(Request $request)
  {
    $this->messenger()->addMessage($this->t('Hemos Recibido el pago y actualizado tu Monedero.'));
    $account = $this->currentUser();
    $path = URL::fromRoute('mi_monedero.monedero', ['user' => $account->id()])->toString();
    $response = new RedirectResponse($path);
    return $response->send();
  }

  public function depositarBackNoOk(Request $request)
  {
    $this->messenger()->addMessage($this->t('Has cancelado el Pago. Por favor, inténtalo de nuevo o elige una forma de pago diferente'));
    $path = URL::fromRoute('mi_monedero.monedero', ['user' => $this->currentUser()->id()])->toString();
    $response = new RedirectResponse($path);
    return $response->send();
  }
  /**
   * Displays a Monedero revision.
   *
   * @param int $monedero_revision
   *   The Monedero revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($monedero_revision)
  {
    $monedero = $this->entityTypeManager()->getStorage('monedero')
      ->loadRevision($monedero_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('monedero');

    return $view_builder->view($monedero);
  }

  /**
   * Page title callback for a Monedero revision.
   *
   * @param int $monedero_revision
   *   The Monedero revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($monedero_revision)
  {
    $monedero = $this->entityTypeManager()->getStorage('monedero')
      ->loadRevision($monedero_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $monedero->label(),
      '%date' => $this->dateFormatter->format($monedero->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Monedero.
   *
   * @param \Drupal\mi_monedero\Entity\MonederoInterface $monedero
   *   A Monedero object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(MonederoInterface $monedero)
  {
    $account = $this->currentUser();
    $monedero_storage = $this->entityTypeManager()->getStorage('monedero');

    $build['#title'] = $this->t('Revisions for %title', ['%title' => $monedero->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all monedero revisions") || $account->hasPermission('administer monedero entities')));
    $delete_permission = (($account->hasPermission("delete all monedero revisions") || $account->hasPermission('administer monedero entities')));

    $rows = [];

    $vids = $monedero_storage->revisionIds($monedero);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\mi_monedero\MonederoInterface $revision */
      $revision = $monedero_storage->loadRevision($vid);
      $username = [
        '#theme' => 'username',
        '#account' => $revision->getRevisionUser(),
      ];

      // Use revision link to link to revisions that are not active.
      $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
      if ($vid != $monedero->getRevisionId()) {
        $link = $this->l($date, new Url('entity.monedero.revision', [
          'monedero' => $monedero->id(),
          'monedero_revision' => $vid,
        ]));
      } else {
        $link = $monedero->link($date);
      }

      $row = [];
      $column = [
        'data' => [
          '#type' => 'inline_template',
          '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
          '#context' => [
            'date' => $link,
            'username' => $this->renderer->renderPlain($username),
            'message' => [
              '#markup' => $revision->getRevisionLogMessage(),
              '#allowed_tags' => Xss::getHtmlTagList(),
            ],
          ],
        ],
      ];
      $row[] = $column;

      if ($latest_revision) {
        $row[] = [
          'data' => [
            '#prefix' => '<em>',
            '#markup' => $this->t('Current revision'),
            '#suffix' => '</em>',
          ],
        ];
        foreach ($row as &$current) {
          $current['class'] = ['revision-current'];
        }
        $latest_revision = FALSE;
      } else {
        $links = [];
        if ($revert_permission) {
          $links['revert'] = [
            'title' => $this->t('Revert'),
            'url' => Url::fromRoute('entity.monedero.revision_revert', [
              'monedero' => $monedero->id(),
              'monedero_revision' => $vid,
            ]),
          ];
        }

        if ($delete_permission) {
          $links['delete'] = [
            'title' => $this->t('Delete'),
            'url' => Url::fromRoute('entity.monedero.revision_delete', [
              'monedero' => $monedero->id(),
              'monedero_revision' => $vid,
            ]),
          ];
        }

        $row[] = [
          'data' => [
            '#type' => 'operations',
            '#links' => $links,
          ],
        ];
      }

      $rows[] = $row;
    }

    $build['monedero_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }
}
