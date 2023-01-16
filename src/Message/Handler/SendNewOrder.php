<?php

namespace App\Message\Handler;

use App\Entity\ClientOrder;
use App\Message\NewOrderMessage;
use App\Services\RHService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SendNewOrder implements MessageHandlerInterface
{
    private $mailer;
    private $em;
    private $rhService;
    private $bus_order;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer, RHService $rhService, MessageBusInterface $bus)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->rhService = $rhService;
        $this->bus_order = $bus;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(NewOrderMessage $message)
    {
        $order = $this->em->getRepository(ClientOrder::class)->find($message->getOrderId());
        $this->rhService->setOrderPayed($order);
        $this->bus_order->dispatch(new NewOrderMessage($order->getId()));

        if (!$this->rhService->setOrderPayed($order)) {
            throw new \Exception("Erreur lors de l'envoi de l'email");
        }
    }
}