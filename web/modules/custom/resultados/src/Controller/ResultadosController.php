<?php

namespace Drupal\resultados\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\resultados\ResultadosBbdd;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Defines HelloController class.
 */
class ResultadosController extends ControllerBase
{

  private $resultados = array();
  private $lnacs = [];
  private $emils = [];
  /**
   * @var \Drupal\resultados\ResultadosBbddd
   */
  protected $resultadosService;

  /**
   * @param \Drupal\resultados\ResultadosBbdd $resultados
   */
  public function __construct(ResultadosBbdd $resultadosService)
  {
    $this->resultadosService = $resultadosService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('resultados.resultadosbbdd')
    );
  }

  public function content()
  {
    $this->resultados['lnac'] = $this->resultadosService->getResultadosJuego('loteria_nacional', 1);
    $this->resultados['emil'] = $this->resultadosService->getResultadosJuego('euromillones', 1);
    $this->resultados['lapr'] = $this->resultadosService->getResultadosJuego('primitiva', 1);
    $this->resultados['bono'] = $this->resultadosService->getResultadosJuego('bonoloto', 1);
    $this->resultados['laqu'] = $this->resultadosService->getResultadosJuego('quiniela', 1);
    $this->resultados['elgr'] = $this->resultadosService->getResultadosJuego('gordo_primitiva', 1);
    $this->resultados['qgol'] = $this->resultadosService->getResultadosJuego('quinigol', 1);
    $this->resultados['lotu'] = $this->resultadosService->getResultadosJuego('lototurf', 1);
    $this->resultados['qupl'] = $this->resultadosService->getResultadosJuego('quintuple_plus', 1);
    return [
      '#theme' => 'page_resultados',
      '#titulo' => 'Últimos Resultados de los Sorteos de Loteria',
      '#resultados' => $this->resultados
    ];
  }
  
  /* API de la veleta, para el led */
  public function dameResultados(Request $request)
  {
    $this->resultados['lnac'] = $this->resultadosService->getResultadosJuego('loteria_nacional', 2);
    $this->resultados['emil'] = $this->resultadosService->getResultadosJuego('euromillones', 1);
    $this->resultados['lapr'] = $this->resultadosService->getResultadosJuego('primitiva', 2);
    $this->resultados['bono'] = $this->resultadosService->getResultadosJuego('bonoloto', 6);
    $this->resultados['laqu'] = $this->resultadosService->getResultadosJuego('quiniela', 1);
    $this->resultados['elgr'] = $this->resultadosService->getResultadosJuego('gordo_primitiva', 1);
    $this->resultados['qgol'] = $this->resultadosService->getResultadosJuego('quinigol', 1);
    $this->resultados['lotu'] = $this->resultadosService->getResultadosJuego('lototurf', 1);
    $this->resultados['qupl'] = $this->resultadosService->getResultadosJuego('quintuple_plus', 1);

    return new JsonResponse([
      'data' => $this->resultados,
      'method' => 'GET',
    ]);
  }
  public function lnac()
  {
    $this->lnacs = $this->resultadosService->getResultadosJuego('loteria_nacional', 9);
    return [
      '#theme' => 'resultados_lnac',
      '#lnacs' => $this->lnacs
    ];
  }

  public function euromillones()
  {
    $this->emils = $this->resultadosService->getResultadosJuego('euromillones', 18);
    return [
      '#theme' => 'resultados_emil',
      '#emils' => $this->emils
    ];
  }

  public function primitiva()
  {
    $this->laprs = $this->resultadosService->getResultadosJuego('primitiva', 18);
    return [
      '#theme' => 'resultados_lapr',
      '#laprs' => $this->laprs
    ];
  }

  public function bonoloto()
  {
    $this->bonos = $this->resultadosService->getResultadosJuego('bonoloto', 18);
    return [
      '#theme' => 'resultados_bono',
      '#bonos' => $this->bonos
    ];
  }

  public function ElGordo()
  {
    $this->elgrs = $this->resultadosService->getResultadosJuego('gordo_primitiva', 18);
    return [
      '#theme' => 'resultados_elgr',
      '#elgrs' => $this->elgrs
    ];
  }

  public function quiniela()
  {
    $this->laqus = $this->resultadosService->getResultadosJuego('quiniela', 18);
    return [
      '#theme' => 'resultados_laqu',
      '#laqus' => $this->laqus
    ];
  }

  public function Quinigol()
  {
    $this->qgols = $this->resultadosService->getResultadosJuego('quinigol', 18);
    return [
      '#theme' => 'resultados_qgol',
      '#qgols' => $this->qgols
    ];
  }

  public function Lototurf()
  {
    $this->lotus = $this->resultadosService->getResultadosJuego('lototurf', 18);
    return [
      '#theme' => 'resultados_lotu',
      '#lotus' => $this->lotus
    ];
  }

  public function QuintuplePlus()
  {
    $this->qupls = $this->resultadosService->getResultadosJuego('quintuple_plus', 18);
    return [
      '#theme' => 'resultados_qupl',
      '#qupls' => $this->qupls
    ];
  }
  protected function getModuleName()
  {
    return 'resultados';
  }
}
