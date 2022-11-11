<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    #[Route('/equipe', name: 'front_team', methods: ['GET'])]
    public function equipe(UserRepository $userRepository): Response
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
    public function show(Category $category): Response
    {
        if (!$category) {
            throw $this->createNotFoundException('La catégorie n’existe pas');
        }
        return $this->render('front/show.html.twig', [
            'category' => $category,
        ]);
    }
}