<?php

namespace Drupal\mi_monedero\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\mi_monedero\Entity\MonederoInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\commerce_redsys\RedsysAPI as RedsysAPI;
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
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }
  /*
  public function __construct($dateFormatter, $renderer)
  {
    $this->dateFormatter = $dateFormatter;
    $this->renderer = $renderer;
  }*/

  public function monederoUser($user)
  {
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
      '#user' => $user
    );
  }
  public function depositarBackOk(Request $request)
  {
    $account = $this->currentUser();
    //$ds_signature_version = $request->request->get('Ds_SignatureVersion');
    $ds_merchantparameters = $request->get('Ds_MerchantParameters');
    //$ds_signature = $request->request->get('Ds_Signature');
    $red = new RedsysAPI;
    $decodec = $red->decodeMerchantParameters($ds_merchantparameters);

    $total = $red->getParameter('Ds_Amount');
    $price = (float) strval($total / 100);
    //var_dump($price);
    if ($account) {
      \Drupal::service('mi_monedero.monedero_manager')->masMonedero($account, $price);

      $this->messenger()->addMessage($this->t('Hemos Recibido y pago y actualizado tu Monedero.'));

      $path = URL::fromRoute('mi_monedero.monedero', ['user' => $account->id()])->toString();
      \Drupal::service('page_cache_kill_switch')->trigger();
      $response = new RedirectResponse($path);
      $response->send();
    }
  }

  public function depositarBackNoOk(Request $request)
  {
    $this->messenger()->addMessage($this->t('Has cancelado el Pago. Por favor, inténtalo de nuevo o elige una forma de pago diferente'));
    // Vuelve al Monedero
    $path = URL::fromRoute('mi_monedero.monedero', ['user' => $this->currentUser()->id()])->toString();
    $response = new RedirectResponse($path);
    $response->send();
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
