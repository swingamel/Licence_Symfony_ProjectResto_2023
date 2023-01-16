<?php

namespace App\Message;

use App\Entity\ClientOrder;

class NewOrderMessage
{
    private int $order_id;

    public function __construct(ClientOrder $order)
    {
        $this->order_id = $order->getId();
    }

    public function getOrderId(): int
    {
        return $this->order_id;
    }

    //Ajoutez y les champs souhaités id de la commande

}