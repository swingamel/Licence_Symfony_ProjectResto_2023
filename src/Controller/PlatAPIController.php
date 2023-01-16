<?php

namespace App\Controller;

use App\Entity\ClientOrder;
use App\Entity\ClientTable;
use App\Entity\Plat;
use App\Entity\User;
use App\Services\RHService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PlatAPIController extends AbstractController
{
    /**
     * @Route("/api/v1/dishes", name="plats_list", methods={"GET"})
     */
    public function listPlatAPI(EntityManagerInterface $em)
    {
        $plats = $em->getRepository(Plat::class)->findAll();

        $data = [];

        foreach ($plats as $plat) {
            $data[] = [
                'id' => $plat->getId(),
                'name' => $plat->getName(),
                'price' => $plat->getPrice(),
                'description' => $plat->getDescription(),
                'image' => $plat->getImage(),
                'category' => $plat->getCategory()->getName(),
            ];
        }

        return new JsonResponse($data);
    }

    //création d'une commande

    /**
     * @Route("/api/v1/order", name="order_create", methods={"POST"})
     * @throws NonUniqueResultException
     */
    public function createOrderAPI(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);
        $tableCommande = $em->getRepository(ClientTable::class)->createQueryBuilder('t')
            ->where('t.id = :id')
            ->setParameter('id', $data['table_commande_id'])
            ->getQuery()
            ->getOneOrNullResult();
        $serveur = $em->getRepository(User::class)->createQueryBuilder('u')
            ->where('u.id = :id')
            ->setParameter('id', $data['serveur_id'])
            ->getQuery()
            ->getOneOrNullResult();
        $plat = new ClientOrder();
        $plat->setDateheureCommande(new \DateTime());
        $plat->setStatutCommande($data['statut_commande']);
        $plat->setPrixCommmande($data['prix_commande']);
        $plat->setTableCommande($tableCommande);
        $plat->setServeur($serveur);
        $plat->setClient($data['client']);
        $em->persist($plat);
        $em->flush();
        return new JsonResponse(['status' => 'Plat created!'], 201);
    }

    //modification d'une commande

    /**
     * @Route("/api/v1/order/{id}", name="order_update", methods={"PUT"})
     * @throws NonUniqueResultException
     */

    public function updateOrderAPI(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, $id)
    {
        $data = json_decode($request->getContent(), true);
        $tableCommande = $em->getRepository(ClientTable::class)->createQueryBuilder('t')
            ->where('t.id = :id')
            ->setParameter('id', $data['table_commande_id'])
            ->getQuery()
            ->getOneOrNullResult();
        $serveur = $em->getRepository(User::class)->createQueryBuilder('u')
            ->where('u.id = :id')
            ->setParameter('id', $data['serveur_id'])
            ->getQuery()
            ->getOneOrNullResult();
        $plat = $em->getRepository(ClientOrder::class)->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
        $plat->setDateheureCommande(new \DateTime());
        $plat->setStatutCommande($data['statut_commande']);
        $plat->setPrixCommmande($data['prix_commande']);
        $plat->setTableCommande($tableCommande);
        $plat->setServeur($serveur);
        $plat->setClient($data['client']);
        $em->persist($plat);
        $em->flush();
        return new JsonResponse(['status' => 'Plat updated!'], 201);
    }

    //Récupérer la liste des commandes en cours, avec leur état

    /**
     * @Route("/api/v1/orders/list", name="orders_list", methods={"GET"})
     */

    public function listOrdersAPI(EntityManagerInterface $em)
    {
        $orders = $em->getRepository(ClientOrder::class)->findAll();

        $data = [];

        foreach ($orders as $order) {
            $data[] = [
                'id' => $order->getId(),
                'dateheure_commande' => $order->getDateheureCommande(),
                'statut_commande' => $order->getStatutCommande(),
                'prix_commande' => $order->getPrixCommmande(),
                'table_commande' => $order->getTableCommande()->getId(),
                'serveur' => $order->getServeur()->getId(),
                'client' => $order->getClient(),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/api/v1/restaurant/today-team", name="get_today_team", methods={"GET"})
     */
    public function getTodayTeamAPI(RhService $rhService): JsonResponse
    {
        $employeeData = $rhService->getDayTeam();
        return new JsonResponse($employeeData);
    }
}