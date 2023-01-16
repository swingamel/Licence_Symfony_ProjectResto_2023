<?php

namespace App\Controller;

use App\Entity\ClientOrder;
use App\Form\ClientOrderType;
use App\Repository\CategoryRepository;
use App\Repository\ClientOrderRepository;
use App\Services\RHService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    private $rhService;
    private $bus_order;
    private $em;

    public function __construct(RHService $rhService, MessageBusInterface $bus, EntityManagerInterface $em)
    {
        $this->rhService = $rhService;
        $this->bus_order = $bus;
        $this->em = $em;
    }

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        $dayTeam = $this->rhService->getDayTeam();

        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
            'day_team' => $dayTeam,
        ]);
    }

    #[Route('/equipe', name: 'front_team', methods: ['GET'])]
    public function equipe(ClientOrderRepository $userRepository): Response
    {
        return $this->render('front/equipe.html.twig', [
            'users' => $userRepository->findAll(),
            'controller_name' => 'FrontController',
        ]);
    }

    #[Route('/carte', name: 'front_dishes', methods: ['GET'])]
    public function carte(CategoryRepository $categoryRepository): Response
    {
        $counts = [];
        foreach ($categoryRepository->findAll() as $category) {
            $counts += [
                $category->getId() => $categoryRepository->countPlats($category->getId()),
            ];
        }

        return $this->render('front/carte.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'controller_name' => 'FrontController',
            'count' => $counts,
        ]);
    }

    #[Route('carte/{id}', name: 'front_dishes_category', methods: ['GET'])]
    public function show(Request $request): Response
    {
        $category = $request->get('category');
        if (!$category) {
            throw $this->createNotFoundException('La catégorie n\'existe pas');
        }
        return $this->render('front/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/support', name: 'front_command_support', methods: ['GET', 'POST'])]
    public function PriseEnChargeCommande(Request $request, ClientOrderRepository $clientOrderRepository): Response
    {
        $clientOrder = new ClientOrder();
        $form = $this->createForm(ClientOrderType::class, $clientOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientOrderRepository->save($clientOrder, true);

            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/support.html.twig', [
            'client_order' => $clientOrder,
            'form' => $form,
        ]);
    }

    #[Route('/clic-collect', name: 'front_clic_collect', methods: ['GET', 'POST'])]
    public function clicCollect(Request $request, ClientOrderRepository $clientOrderRepository): Response
    {
        $clientOrder = new ClientOrder();
        $form = $this->createForm(ClientOrderType::class, $clientOrder);
        $form->handleRequest($request);
        //faire le total de la commande
        $total = 0;
        foreach ($clientOrder->getPlats() as $plat) {
            $total += $plat->getPrice();
        }
        $clientOrder->setPrixCommmande($total);
        if ($form->isSubmitted() && $form->isValid()) {
            $clientOrderRepository->save($clientOrder, true);
            $this->addFlash('success', 'Votre commande a bien été prise en compte');
            $this->bus_order->dispatch($clientOrder);
            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->render('front/confirmation_order.html.twig', [
                'clientOrder' => $clientOrder,
            ]);
        }

        return $this->render('front/clic_collect.html.twig', [
            'form' => $form->createView(),
            'client_order' => $clientOrder,
            'total' => $total,
        ]);
    }

    #[Route('/list-order', name: 'app_client_order_index', methods: ['GET'])]
    public function listOrder(ClientOrderRepository $clientOrderRepository): Response
    {
        return $this->render('front/list_order.html.twig', [
            'client_orders' => $clientOrderRepository->findAll(),
        ]);
    }

    #[Route('detail_order/{id}', name: 'app_order_show', methods: ['GET'])]
    public function showOrder(ClientOrder $clientOrder): Response
    {
        //récupérer le total de la commande
        $total = 0;
        foreach ($clientOrder->getPlats() as $plat) {
            $total += $plat->getPrice();
        }
        //recupérer tout les plats de la commande
        $plats = $clientOrder->getPlats();
        return $this->render('front/detail_order.html.twig', [
            'client_order' => $clientOrder,
            'total' => $total,
            'plats' => $plats
        ]);
    }
}