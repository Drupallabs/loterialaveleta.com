<?php

namespace Drupal\sorteos\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\sorteos\SorteosBbdd;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class SorteosController extends ControllerBase
{
    /**
     * @var \Drupal\sorteos\SorteosBbddd
     */
    protected $sorteosService;

    /**
     * @param \Drupal\sorteos\SorteosBbdd $sorteos
     */
    public function __construct(SorteosBbdd $sorteosService)
    {
        $this->sorteosService = $sorteosService;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('sorteos.sorteosbbdd')
        );
    }

    public function proximoSorteo($gameid, Request $request)
    {
        $sorteo = $this->sorteosService->proximoSorteo($gameid);

        $resarr = [
            'data' => [$sorteo],
            'method' => 'GET',
        ];
        return new JsonResponse($resarr);
    }

    public function ultimosSorteos($gameid, Request $request)
    {
        $sorteos = $this->sorteosService->ultimosSorteos($gameid);
        return new JsonResponse($sorteos);
    }


    public function dameResultadoPdf($sorteoid, Request $request)
    {
        $sorteo = $this->sorteosService->dameResultadoPdf($sorteoid);
        return new JsonResponse($sorteo);
    }
}
