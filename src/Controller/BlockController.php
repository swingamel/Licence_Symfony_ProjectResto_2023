<?php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Plat;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlockController extends AbstractController
{
    #[Route('/block/{max}', name: 'app_block_show', methods: ['GET'])]
    public function dayDishesAction($max = 3, EntityManagerInterface $em) : Response
    {
        $dishes = $em->getRepository(Plat::class)->findBy([], ['id' => 'DESC'], $max);

        return $this->render('Partials/day_dishes.html.twig', [
            'dishes' => $dishes,
        ]);
    }
/*    public function dayDishes(ManagerRegistry $doctrine, PlatRepository $dishRepository, $max = 3): Response
    {
        $category = $doctrine->getRepository(Category::class)->findOneBy(['Name' => 'Plats']);
        $dishes = $dishRepository->findStickies($category, $max);
        return $this->render(
            'Partials/day_dishes.html.twig',
            array('dishes' => $dishes)
        );
    }*/
}
