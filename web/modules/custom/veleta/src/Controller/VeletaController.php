<?php

namespace Drupal\veleta\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\botes\Botes;
use Drupal\resultados\ResultadosBbdd;
use Drupal\Core\State\State;

/**
 * Class VeletaController.
 */
class VeletaController extends ControllerBase
{

  private $botes = array();
  private $resultados = array();
  /**
   * @var \Drupal\botes\Botes
   */
  protected $botesService;
  /**
   * @var \Drupal\resultados\ResultadosBbdd
   */
  protected $resultadosService;

  /**
   * The state
   * @var \Drupal\Core\State\State
   */
  protected $state;

  /**
   * @param \Drupal\botes\Botes $botes
   * @param \Drupal\resultados\ResultadosBbdd $resultados
   * @param \Drupal\Core\State\State
   */
  public function __construct(Botes $botesService, ResultadosBbdd $resultadosService, State $state)
  {
    $this->botesService = $botesService;
    $this->resultadosService = $resultadosService;
    $this->state = $state;
  }


  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('botes.botes'),
      $container->get('resultados.resultadosbbdd'),
      $container->get('state')
    );
  }
  public function dashboard()
  {

    return array(
      '#theme' => 'dashboard',
      '#lastimport' => $this->state->get('sorteos.last_import'),
      '#lastpaga' => $this->state->get('premios.last_paga')
    );
  }
  public function led()
  {

    $this->resultados['lnac'] = $this->resultadosService->getResultadosJuego('LNAC', 2);
    $this->resultados['emil'] = $this->resultadosService->getResultadosJuego('EMIL', 1);
    $this->resultados['lapr'] = $this->resultadosService->getResultadosJuego('LAPR', 2);
    $this->resultados['bono'] = $this->resultadosService->getResultadosJuego('BONO', 6);
    $this->resultados['laqu'] = $this->resultadosService->getResultadosJuego('LAQU', 1);
    $this->resultados['elgr'] = $this->resultadosService->getResultadosJuego('ELGR', 1);
    $this->resultados['qgol'] = $this->resultadosService->getResultadosJuego('QGOL', 1);
    $this->resultados['lotu'] = $this->resultadosService->getResultadosJuego('LOTU', 1);
    $this->resultados['qupl'] = $this->resultadosService->getResultadosJuego('QUPL', 1);

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
      '#theme' => 'led', '#botes' => $this->botes, '#resultados' => $this->resultados,
    );
  }
}
