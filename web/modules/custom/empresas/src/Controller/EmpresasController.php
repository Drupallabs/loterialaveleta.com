<?php

namespace Drupal\empresas\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Database\Database;


class EmpresasController extends ControllerBase
{
  public function index()
  {
    return array(
      '#theme' => 'empresas',
    );
  }

  public function exportarDatos(Request $request)
  {
    $pedidos = [];
    $headers = [];
    $empresa = $request->query->get('empresa');
    $numero = $request->query->get('numero');
    $codigo = $request->query->get('codigo');
    $email = $request->query->get('email');

    $form  = \Drupal::formBuilder()->getForm('Drupal\empresas\Form\EmpresasListadoForm');
    if (!$empresa) {
      \Drupal::messenger()->addError('Debes Seleccionar una empresa');
    }
    if ($empresa || $numero || $email || $codigo) {

      $headers = array('Pedido ', 'Codigo TPV', 'Empresa', 'Correo electrónico', 'Nombre', 'DNI', 'Cantidad', 'Numero Decimo', 'Total Linea', 'Total Pedido', 'Fecha','Pago Premio', 'PDF');

      $query = $this->getQuery($empresa, $numero, $codigo, $email);
      $result = $query->execute();

      foreach ($result as $record) {
        $pedidos[] = [
          'pedido' => $record->order_number,
          'codigo_tpv' => $record->order_id,
          'empresa' => $record->nombre_empresa,
          'usuario' => $record->name,
          'email' => $record->mail,
          'nombre' => $record->field_nombre_value,
          'dni' => $record->field_dni_value,
          'producto_id' => $record->product_id,
          'cantidad' => $record->quantity,
          'numero' => $record->field_numero_decimo_value,
          'total_linea' => $record->total_linea,
          'total' => $record->total_pedido,
          'ip' => $record->ip_address,
          'fecha' => $record->completed,
          'pago_premio' => $record->pago_premio
        ];
      }
    }


    $output[] = [
      '#theme' => 'empresas-exportar-datos',
      '#form' => $form,
      '#header_data' => $headers,
      '#pedidos' => $pedidos,
    ];
    return $output;
  }

  private function getQuery($empresa = null, $numero = null, $codigo = null, $email = null)
  {

    $db_connection = Database::getConnection('default');
    $query = $db_connection->select('commerce_order', 'co');

    $query->join('users_field_data', 'u', 'co.uid = u.uid');
    $query->join('commerce_order__field_nombre', 'cn', 'co.order_id = cn.entity_id');
    $query->join('commerce_order__field_dni', 'cdni', 'co.order_id = cdni.entity_id');
    $query->join('commerce_order_item', 'coi', 'co.order_id = coi.order_id');
    $query->join('commerce_order__order_items', 'cois', 'coi.order_item_id = cois.order_items_target_id');
    $query->join('commerce_product_variation_field_data', 'cov', 'coi.purchased_entity = cov.variation_id');
    $query->join('commerce_product', 'cp', 'cov.product_id = cp.product_id');
    $query->join('commerce_product__field_numero_decimo', 'cpn', 'cp.product_id = cpn.entity_id');
    $query->join('commerce_product__field_empresa', 'cpe', 'cp.product_id = cpe.entity_id');
    $query->join('empresa', 'e', 'cpe.field_empresa_target_id = e.id');
    $query->leftJoin('commerce_order__field_pago_premio_pedido', 'copp', 'co.order_id = copp.entity_id');

    $query->fields(
      'co',
      ['order_id', 'order_number', 'mail', 'ip_address', 'completed', 'total_price__number']
    );
    $query->fields(
      'u',
      ['name']
    );
    $query->fields(
      'cn',
      ['field_nombre_value']
    );
    $query->fields(
      'cdni',
      ['field_dni_value']
    );
    $query->fields(
      'coi',
      ['quantity']
    );


    $query->addField('co', 'total_price__number', 'total_pedido');
    $query->addField('coi', 'total_price__number', 'total_linea');
    $query->addField('cp', 'product_id');
    $query->addField('cpn', 'field_numero_decimo_value');
    $query->addField('e', 'nombre', 'nombre_empresa');
    $query->addField('copp', 'field_pago_premio_pedido_value', 'pago_premio');

    $query->condition('co.state', 'completed');
    if ($empresa)
      $query->condition('cpe.field_empresa_target_id', $empresa);
    if ($numero)
      $query->condition('cpn.field_numero_decimo_value', $numero);
    if ($codigo)
      $query->condition('co.order_id', $codigo);
    if ($email)
      $query->condition('co.mail', $email);

    $query->orderBy('co.completed', 'DESC');

    $tempstore = \Drupal::service('user.private_tempstore')->get('empresas');
    $tempstore->set('empresas_query', $query);
    return $query;
  }
}
