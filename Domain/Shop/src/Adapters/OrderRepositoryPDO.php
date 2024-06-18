<?php

namespace Softadastra\Domain\Shop\Adapters;

use Softadastra\Domain\Shop\Entity\OrderEntity;
use Softadastra\Domain\Shop\Model\ModelRepositoryPDO;
use Softadastra\Domain\Shop\Port\OrderRepositoryInterface;
use PDO;

class OrderRepositoryPDO extends ModelRepositoryPDO implements OrderRepositoryInterface
{
    protected $table = 'tbl_order';
    protected $id = 'order_id';

    public function save(OrderEntity $order)
    {
        $query = $this->pdo->getPdo()->prepare(
            "INSERT INTO {$this->table} (user_id, shipping_address, payment_method) 
            VALUES (:user_id, :shipping_address, :payment_method)"
        );

        $query->execute([
            'user_id' => $order->getUserId(),
            'shipping_address' => $order->getShippingAddress(),
            'payment_method' => $order->getPaymentMethod()
        ]);
    }

    public function findAll()
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table}");
        $query->execute();
        $ordersData = $query->fetchAll(PDO::FETCH_ASSOC);

        $orders = [];
        foreach ($ordersData as $orderData) {
            $order = new OrderEntity(
                $orderData['user_id'],
                $orderData['shipping_address'],
                $orderData['payment_method']
            );

            $order->setId($orderData['id']);

            $orders[] = $order;
        }

        return $orders;
    }

    public function findById($id)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE {$this->id} = :id");
        $query->execute(['id' => $id]);
        $orderData = $query->fetch(PDO::FETCH_ASSOC);

        if (!$orderData) {
            return null;
        }

        $order = new OrderEntity(
            $orderData['user_id'],
            $orderData['shipping_address'],
            $orderData['payment_method']
        );
        $order->setId($orderData['order_id']);

        return $order;
    }

    public function findByUserId($id)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE user_id = :id");
        $query->execute(['id' => $id]);
        $orderData = $query->fetch(PDO::FETCH_ASSOC);

        if (!$orderData) {
            return null;
        }

        $order = new OrderEntity(
            $orderData['user_id'],
            $orderData['shipping_address'],
            $orderData['payment_method']
        );
        $order->setId($orderData['order_id']);

        return $order;
    }
}
