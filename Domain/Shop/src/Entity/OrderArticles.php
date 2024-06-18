<?php

namespace Softadastra\Domain\Shop\Entity;

class OrderArticles
{
    private $order_id;
    private $order_date;
    private $user_id;
    private $full_name;
    private $setSellerName;
    private $telephone;
    private $total_amount;
    private $shipping_address;
    private $payment_method;
    private $order_status;
    private $orderUserId;

    public function getOrderId()
    {
        return $this->order_id;
    }

    public function getOrderDate()
    {
        return $this->order_date;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getFullName()
    {
        return $this->full_name;
    }

    public function setFullName($client)
    {
        $this->full_name = $client;
    }


    public function setSellerName($setSellerName)
    {
        $this->setSellerName = $setSellerName;
    }

    public function getSellerName()
    {
        return $this->setSellerName;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    public function getTotalAmount()
    {
        return $this->total_amount;
    }

    public function getShippingAddress()
    {
        return $this->shipping_address;
    }

    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    public function getOrderStatus()
    {
        return $this->order_status;
    }

    public function getOrderUserId()
    {
        return $this->orderUserId;
    }
    private $orderQuantity;
    public function setOrderDetails($orderUserId)
    {
        $this->orderUserId = $orderUserId;
    }

    public function getOrderQuantity()
    {
        return $this->orderQuantity;
    }
}
