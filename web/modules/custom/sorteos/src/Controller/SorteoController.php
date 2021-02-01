<?php

namespace Drupal\sorteos\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\botes\BotesBbdd;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class BotesController extends ControllerBase
{

    public function proximoSorteo($gameid)
    {

        $resarr = [
            'data' => [$gameid],
            'method' => 'GET',
        ];
        return new JsonResponse($resarr);
    }
}