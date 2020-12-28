<?php

namespace Drupal\empresas;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;

class EmpresasBbdd
{
    /**
     * The db connection.
     *
     * @var \Drupal\Core\Database\Connection
     */
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public static function create(ContainerInterface $container)
    {
        return new static($container->get('database'));
    }

    public function damePedido($codigo)
    {

        $query = $this->connection->select('commerce_order', 'co');

        $query->join('users_field_data', 'u', 'co.uid = u.uid');
        $query->join('commerce_order__field_nombre', 'cn', 'co.order_id = cn.entity_id');
        $query->join('commerce_order__field_dni', 'cdni', 'co.order_id = cdni.entity_id');
        $query->join('commerce_order_item', 'coi', 'co.order_id = coi.order_id');
        $query->join('commerce_order__order_items', 'cois', 'coi.order_item_id = cois.order_items_target_id');
        $query->join('commerce_product_variation_field_data', 'cov', 'coi.purchased_entity = cov.variation_id');
        $query->join('commerce_product', 'cp', 'cov.product_id = cp.product_id');
        $query->join('commerce_product__field_numero_decimo', 'cpn', 'cp.product_id = cpn.entity_id');

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

        $query->condition('co.state', 'completed');

        $query->condition('co.order_id', $codigo);

        $query->orderBy('co.completed', 'DESC');

        return $query->execute();
    }
}
