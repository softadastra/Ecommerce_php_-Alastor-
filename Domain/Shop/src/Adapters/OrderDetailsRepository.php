<?php

namespace Softadastra\Domain\Shop\Adapters;

use PDO;
use Softadastra\Domain\Shop\Entity\OrderDetailsEntity;
use Softadastra\Domain\Shop\Model\ModelRepositoryPDO;

class OrderDetailsRepository extends ModelRepositoryPDO
{
    protected $table = 'tbl_order_details';

    public function findById($id)
    {
        $query = $this->pdo->getPdo()->prepare("SELECT * FROM {$this->table} WHERE order_id = :id");
        $query->execute(['id' => $id]);
        $orderDetailsData = $query->fetch(PDO::FETCH_ASSOC);

        if (!$orderDetailsData) {
            return null;
        }

        $orderDetails = new OrderDetailsEntity(
            $orderDetailsData['order_id'],
            $orderDetailsData['article_id'],
            $orderDetailsData['quantity']
        );

        $orderDetails->setId($orderDetailsData['id']);
        $orderDetails->setColor($orderDetailsData['color']);
        $orderDetails->setSize($orderDetailsData['size']);

        return $orderDetails;
    }
}
