<?php

namespace App\Listener;

use App\Entity\ClientOrder;
use App\Event\OrderPayedEvent;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Security\Core\Security;

class OrderSubscriber implements EventSubscriber
{
    private $security;
    private $mailer;

    public function __construct(Security $security, Mailer $mailer, EventDispatcher $dispatcher)
    {
        $this->security = $security;
        $this->mailer = $mailer;
        $this->dispatcher = $dispatcher;
    }
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
            'postPersist',
            'postUpdate',
        ];
    }

    private function setOrderDate(ClientOrder $order)
    {
        $order->setDateheureCommande(new \DateTime());
    }

    private function setOrderStatus(ClientOrder $order)
    {
        $order->setStatutCommande("Prise");
    }

    private function calculateTotalAmount(ClientOrder $order)
    {
        $order->setPrixCommmande(array_sum(array_column($order->getPlats()->toArray(), 'prix')));
    }

    public function preFlush(PreFlushEventArgs $args)
    {
        $entityManager = $args->getEntityManager();
        $unitOfWork = $entityManager->getUnitOfWork();

        foreach ($unitOfWork->getScheduledEntityInsertions() as $entity) {
            if (!$entity instanceof ClientOrder) {
                continue;
            }
            $this->setOrderDate($entity);
            $this->setOrderStatus($entity);

            $this->calculateTotalAmount($entity);
        }
    }

    public function prePersist(PreFlushEventArgs $args)
    {
        $this->preFlush($args);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof ClientOrder) {
            return;
        }

        $this->calculateTotalAmount($entity);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof ClientOrder) {
            return;
        }

        $this->handleOrderUpdate($entity);

        $this->dispatcher->dispatch(new OrderPayedEvent($entity));
    }

    private function sendEmailToKitchen($kitchenEmail)
    {
        $subject = 'Nouvelle commande reçue';
        $body = 'Une nouvelle commande a été passée en salle à manger.';
        $headers = 'From: melanie.debeaulieu@gmail.com' . "\r\n" .
            'Reply-To: melanie.debeaulieu@gmail.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($kitchenEmail, $subject, $body, $headers);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof ClientOrder) {
            return;
        }

        $this->sendEmailToKitchen($entity);
    }

    private function sendEmailToWaiter($serverEmail)
    {
        $subject = 'Commande prête';
        $body = 'Votre commande est prête à être servie.';
        $headers = 'From: melanie.debeaulieu@gmail.com' . "\r\n" .
            'Reply-To: melanie.debeaulieu@gmail.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($serverEmail, $subject, $body, $headers);
    }

    private function sendEmailToServer($frontDeskEmail)
    {
        $subject = 'La commande est prête à être facturée';
        $message = 'Une commande a été servie et est prête à être facturée.';
        $headers = 'From: melanie.debeaulieu@gmail.com' . "\r\n" .
            'Reply-To: melanie.debeaulieu@gmail.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($frontDeskEmail, $subject, $message, $headers);
    }

    private function sendEmailToReception($serverEmail)
    {
        $subject = 'La commande est payée';
        $message = 'Votre commande a été payée.';
        $headers = 'From: melanie.debeaulieu@gmail.com' . "\r\n" .
            'Reply-To: melanie.debeaulieu@gmail.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($serverEmail, $subject, $message, $headers);
    }

    private function handleOrderUpdate(ClientOrder $order)
    {
        switch ($order->getStatutCommande()) {
            case 'Preparée':
                $this->sendEmailToWaiter($order);
                break;
            case 'Servie':
                $this->sendEmailToServer($order);
                break;
            case 'Payée':
                $this->sendEmailToReception($order);
                break;
        }
    }
}