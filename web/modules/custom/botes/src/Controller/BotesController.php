<?php

namespace Drupal\botes\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\botes\BotesBbdd;
use Drupal\botes\DameBotes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class BotesController extends ControllerBase
{

  private $botes = array();


  /**
   * @var \Drupal\botes\BotesBbdd
   */
  protected $botesServiceBbdd;

  /**
   * Botes constructor.
   *
   * @param \Drupal\botes\BotesBbdd $botesServiceBbdd
   */
  public function __construct(BotesBbdd $botesServiceBbdd)
  {
    $this->botesService = $botesServiceBbdd;
  }


  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static($container->get('botes.botesbbdd'));
  }
  /**
   * Todos.
   *
   * @return string
   *   Return Hello s tring.
   */
  public function todos()
  {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: todos')
    ];
  }

  public function botes()
  {
    $this->botes['emil'] = $this->botesService->getBote('EMIL');
    $this->botes['lapr'] = $this->botesService->getBote('LAPR');
    $this->botes['bono'] = $this->botesService->getBote('BONO');
    $this->botes['elgr'] = $this->botesService->getBote('ELGR');
    $this->botes['lnac'] = $this->botesService->getBote('LNAC');
    $this->botes['laqu'] = $this->botesService->getBote('LAQU');
    $this->botes['qgol'] = $this->botesService->getBote('QGOL');
    $this->botes['lotu'] = $this->botesService->getBote('LOTU');
    $this->botes['qupl'] = $this->botesService->getBote('QUPL');
    return array(
      '#theme' => 'botes_theme_hook',
      '#botes' => $this->botes,
    );
  }

  /*
  * API para pantalla.loterialaveleta.com
  */
  public function dameBotes(Request $request)
  {
    $this->botes['emil'] = $this->botesService->getBote('EMIL');
    $this->botes['lapr'] = $this->botesService->getBote('LAPR');
    $this->botes['bono'] = $this->botesService->getBote('BONO');
    $this->botes['elgr'] = $this->botesService->getBote('ELGR');
    $this->botes['lnac'] = $this->botesService->getBote('LNAC');
    $this->botes['laqu'] = $this->botesService->getBote('LAQU');
    $this->botes['qgol'] = $this->botesService->getBote('QGOL');
    $this->botes['lotu'] = $this->botesService->getBote('LOTU');
    $this->botes['qupl'] = $this->botesService->getBote('QUPL');
    $resarr = [
      'data' => $this->botes,
      'method' => 'GET',
    ];
    return new JsonResponse($resarr);
  }
}
