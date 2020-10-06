<?php

namespace Drupal\commerce_redsys\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\commerce_redsys\RedsysAPI as RedsysAPI;

class CommerceRedsysController extends ControllerBase
{

  public function Notificacion(Request $request)
  {
    $logger = \Drupal::logger('commerce_redsys');
    //$logger->info('Recibiendo notificacion. CommerceRedsysController::Notificacion');
    $mailManager = \Drupal::service('plugin.manager.mail');

    $module = 'commerce_redsys';
    $key = 'pago_recibido';
    $to = 'pedidos@loterialaveleta.com';

    //$ds_signature_version = $request->request->get('Ds_SignatureVersion');
    $ds_merchantparameters = $request->request->get('Ds_MerchantParameters');
    //$ds_signature = $request->request->get('Ds_Signature');

    $red = new RedsysAPI;

    $decodec = $red->decodeMerchantParameters($ds_merchantparameters);

    $params['order'] = $red->getParameter('Ds_Order');
    $params['amount'] = $red->getParameter('Ds_Amount');
    $params['date'] = $red->getParameter('Ds_Date');
    $params['hour'] = $red->getParameter('Ds_Hour');
    $params['response'] = $red->getParameter('Ds_Response'); // Valor que indica el resultado de la operacion
    $params['securepayment'] = $red->getParameter('Ds_SecurePayment');
    $params['cardtype'] = $red->getParameter('Ds_Card_Type');
    $params['cardbrand'] = $red->getParameter('Ds_Card_Brand');

    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = true;

    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
    if ($result['result'] !== true) {
      $logger->info('Error en el envio de email CommerceRedsysController::Notificacion');
    } else {
      $logger->info('Email de notificacion de pago recibido enviado CommerceRedsysController::Notificacion');
    }

    $response = [
      "result" => "ok",
      "message" => "Todo ba bien",
    ];

    return new JsonResponse([
      'data' => $response,
      'method' => 'GET',
    ]);
  }

  public function NotificacionError(Request $request)
  {
    $logger = \Drupal::logger('commerce_redsys');
    //$logger->info('Recibiendo notificacion. CommerceRedsysController::Notificacion');
    $mailManager = \Drupal::service('plugin.manager.mail');

    $module = 'commerce_redsys';
    $key = 'pago_recibido_error';
    $to = 'pedidos@loterialaveleta.com';

    //$ds_signature_version = $request->request->get('Ds_SignatureVersion');
    $ds_merchantparameters = $request->request->get('Ds_MerchantParameters');
    //$ds_signature = $request->request->get('Ds_Signature');

    $red = new RedsysAPI;

    $decodec = $red->decodeMerchantParameters($ds_merchantparameters);

    $params['order'] = $red->getParameter('Ds_Order');
    $params['amount'] = $red->getParameter('Ds_Amount');
    $params['date'] = $red->getParameter('Ds_Date');
    $params['hour'] = $red->getParameter('Ds_Hour');
    $params['response'] = $red->getParameter('Ds_Response'); // Valor que indica el resultado de la operacion
    $params['securepayment'] = $red->getParameter('Ds_SecurePayment');
    $params['cardtype'] = $red->getParameter('Ds_Card_Type');
    $params['cardbrand'] = $red->getParameter('Ds_Card_Brand');

    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = true;

    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
    if ($result['result'] !== true) {
      $logger->info('Error en el envio de email CommerceRedsysController::Notificacion');
    } else {
      $logger->info('Email de notificacion de pago recibido enviado CommerceRedsysController::Notificacion');
    }

    $response = [
      "result" => "ok",
      "message" => "Todo ba bien",
    ];

    return new JsonResponse([
      'data' => $response,
      'method' => 'GET',
    ]);
  }
}
