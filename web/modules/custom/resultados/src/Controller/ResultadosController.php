<?php

namespace Drupal\resultados\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\resultados\ResultadosBbdd;
use Drupal\sorteos\SorteosBbdd;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class ResultadosController extends ControllerBase
{

  private $resultados = array();
  private $lnacs = [];
  private $emils = [];
  private $bonos = [];
  private $laprs = [];
  private $elgrs = [];
  private $laqus = [];
  private $qgols = [];
  private $lotus = [];
  private $qupls = [];
  private $diassemanaN = [];
  private $mesesN = [];

  /**
   * @var \Drupal\resultados\ResultadosBbddd
   */
  protected $resultadosService;

  /**
   * @var \Drupal\sorteos\SorteosBbddd
   */
  protected $sorteosService;

  /**
   * @param \Drupal\resultados\ResultadosBbdd $resultados
   * @param \Drupal\sorteos\SorteosBbdd $sorteos
   */
  public function __construct(ResultadosBbdd $resultadosService, SorteosBbdd $sorteosService)
  {
    $this->resultadosService = $resultadosService;
    $this->sorteosService = $sorteosService;
    $this->diassemanaN = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $this->mesesN = array(1 => "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('resultados.resultadosbbdd'),
      $container->get('sorteos.sorteosbbdd')
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
    $this->lnacs = $this->resultadosService->getResultadosJuego('loteria_nacional', 18);
    list($proximo, $bote) = $this->dameProximoSorteo('LNAC');
    return [
      '#theme' => 'resultados_lnac',
      '#lnacs' => $this->lnacs,
      '#proximo' => $proximo
    ];
  }

  public function euromillones()
  {
    $this->emils = $this->resultadosService->getResultadosJuego('euromillones', 18);
    list($proximo, $bote) = $this->dameProximoSorteo('EMIL');
    return [
      '#theme' => 'resultados_emil',
      '#emils' => $this->emils,
      '#proximo' => $proximo,
      '#bote' => $bote
    ];
  }

  public function primitiva()
  {
    $this->laprs = $this->resultadosService->getResultadosJuego('primitiva', 18);
    list($proximo, $bote) = $this->dameProximoSorteo('LAPR');
    return [
      '#theme' => 'resultados_lapr',
      '#laprs' => $this->laprs,
      '#proximo' => $proximo
    ];
  }

  public function bonoloto()
  {
    $this->bonos = $this->resultadosService->getResultadosJuego('bonoloto', 18);
    list($proximo, $bote) = $this->dameProximoSorteo('BONO');
    return [
      '#theme' => 'resultados_bono',
      '#bonos' => $this->bonos,
      '#proximo' => $proximo
    ];
  }

  public function ElGordo()
  {
    $this->elgrs = $this->resultadosService->getResultadosJuego('gordo_primitiva', 18);
    list($proximo, $bote) = $this->dameProximoSorteo('ELGR');
    return [
      '#theme' => 'resultados_elgr',
      '#elgrs' => $this->elgrs,
      '#proximo' => $proximo
    ];
  }

  public function quiniela()
  {
    $this->laqus = $this->resultadosService->getResultadosJuego('quiniela', 18);
    list($proximo, $bote) = $this->dameProximoSorteo('LAQU');
    return [
      '#theme' => 'resultados_laqu',
      '#laqus' => $this->laqus,
      '#proximo' => $proximo
    ];
  }

  public function Quinigol()
  {
    $this->qgols = $this->resultadosService->getResultadosJuego('quinigol', 18);
    list($proximo, $bote) = $this->dameProximoSorteo('QGOL');
    return [
      '#theme' => 'resultados_qgol',
      '#qgols' => $this->qgols,
      '#proximo' => $proximo
    ];
  }

  public function Lototurf()
  {
    $this->lotus = $this->resultadosService->getResultadosJuego('lototurf', 18);
    list($proximo, $bote) = $this->dameProximoSorteo('LOTU');
    return [
      '#theme' => 'resultados_lotu',
      '#lotus' => $this->lotus,
      '#proximo' => $proximo
    ];
  }

  public function QuintuplePlus()
  {
    $this->qupls = $this->resultadosService->getResultadosJuego('quintuple_plus', 18);
    list($proximo, $bote) = $this->dameProximoSorteo('QUPL');
    return [
      '#theme' => 'resultados_qupl',
      '#qupls' => $this->qupls,
      '#proximo' => $proximo
    ];
  }

  private function dameProximoSorteo($gameid)
  {
    $proximoSorteo = $this->sorteosService->proximoSorteo($gameid);
    if ($proximoSorteo) {
      $d = date('d', strtotime($proximoSorteo->fecha));
      $mes = date('n', strtotime($proximoSorteo->fecha));
      $dia = date('w', strtotime($proximoSorteo->fecha));
      return [$this->diassemanaN[$dia] . ', ' . (int)$d . ' de ' . $this->mesesN[$mes], $proximoSorteo->premio_bote];
    } else {
      return '';
    }
  }

  protected function getModuleName()
  {
    return 'resultados';
  }
}
