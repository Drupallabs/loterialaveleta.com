<?php

namespace Drupal\mi_monedero;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\AccountProxy;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\commerce_price\Calculator;

/**
 * Monedero manager class.
 */
class MonederoManager
{

    use \Drupal\Core\StringTranslation\StringTranslationTrait;

    /**
     * The entity type manager.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManager;

    /**
     * The db connection.
     *
     * @var \Drupal\Core\Database\Connection
     */
    protected $connection;

    /**
     * AccountProxy definition.
     *
     * @var \Drupal\Core\Session\AccountProxy
     */
    protected $currentUser;

    /**
     * Class constructor.
     */
    public function __construct(EntityTypeManagerInterface $entity_type_manager, Connection $connection, AccountProxy $current_user)
    {
        $this->entityTypeManager = $entity_type_manager;
        $this->connection = $connection;
        $this->currentUser = $current_user;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('entity_type.manager'),
            $container->get('database'),
            $container->get('current_user')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function loadAccountBalance(AccountInterface $account)
    {
        // Check if issuer monedero exists.
        $monedero = $this->connection->query("SELECT * FROM monedero WHERE user_id = :uid", [
            ':uid' => $account->id(),
        ])->fetchObject();

        return $monedero;
    }

    public function updateMonedero(AccountInterface $account, float $total)
    {
        // Sacamos el saldo actual y lo restamos con lo que vale el pedido
        $monedero = $this->connection->query("SELECT * FROM monedero WHERE user_id = :uid", [
            ':uid' => $account->id(),
        ])->fetchObject();

        $saldo = Calculator::subtract((string) $monedero->cantidad, (string) $total, 2);

        $mones = reset($this->entityTypeManager->getStorage('monedero')->loadByProperties(['user_id' =>  $account->id()]));
        $mones->cantidad = $saldo;
        $mones->save();
    }

    public function masMonedero(AccountInterface $account, float $total)
    {
        // Sacamos el saldo actual y lo restamos con lo que vale el pedido
        $monedero = $this->connection->query("SELECT * FROM monedero WHERE user_id = :uid", [
            ':uid' => $account->id(),
        ])->fetchObject();
        if ($monedero->cantidad == "") {
            $cantidad = 0;
        } else {
            $cantidad = $monedero->cantidad;
        }

        $saldo = Calculator::add((string) $cantidad, (string) $total, 2);

        $mones = reset($this->entityTypeManager->getStorage('monedero')->loadByProperties(['user_id' =>  $account->id()]));
        // si ya tiene un monedero lo actualiza, sino lo crea
        if ($mones) {
            $mones->cantidad = $saldo;
        } else {
            $mones = $this->entityTypeManager->getStorage('monedero')
                ->create([
                    'user_id' => $account->id(),
                    'name' => 'Monedero',
                    'cantidad' => $saldo
                ]);
        }
        $mones->save();
    }
}
