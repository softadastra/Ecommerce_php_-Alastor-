<?php

namespace Softadastra\Domain\Shop\Entity;

class OrderDetailsEntity
{
    private $id;
    private $orderId;
    private $articleId;
    private $quantity;
    private $color;
    private $size;

    public function __construct($orderId, $articleId, $quantity)
    {
        $this->orderId = $orderId;
        $this->articleId = $articleId;
        $this->quantity = $quantity;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    public function getArticleId()
    {
        return $this->articleId;
    }

    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }
}
