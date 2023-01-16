<?php

namespace App\Event;

use App\Entity\ClientOrder;
use Doctrine\Common\EventSubscriber;

class OrderPayedEvent
{
    //créez un listener sur votre propre évènement order.payed
    private $order;

    public function __construct(ClientOrder $order)
    {
        $this->order = $order;
    }

    public function getOrder(): ClientOrder
    {
        return $this->order;
    }
}