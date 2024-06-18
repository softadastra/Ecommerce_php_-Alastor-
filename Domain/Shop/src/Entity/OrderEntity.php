<?php

namespace Softadastra\Domain\Shop\Entity;

class OrderEntity
{
    private $id;
    private $userId;
    private $shippingAddress;
    private $paymentMethod;

    public function __construct($userId, $shippingAddress, $paymentMethod)
    {
        $this->userId = $userId;
        $this->shippingAddress = $shippingAddress;
        $this->paymentMethod = $paymentMethod;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }
}
