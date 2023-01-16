<?php

namespace App\Controller;

use App\Entity\Allergen;
use App\Entity\Category;
use App\Entity\Plat;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_home', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/import-dishes', name: 'admin_import_dishes', methods: ['GET'])]
    public function importDishesAction(EntitymanagerInterface $em): Response
    {
        $json = file_get_contents('../public/Json/dish.json');
        $dishes = json_decode($json, true);

        $dishRepository = $em->getRepository(Plat::class);
        $categoryRepository = $em->getRepository(Category::class);
        $allergenRepository = $em->getRepository(Allergen::class);
        $userRepository = $em->getRepository(User::class);

        foreach (["dessert", "entrees", "plat"] as $type) {
            $category = $categoryRepository->findOneBy(array("Name" => ucfirst($type)));

            if ($category && isset($dishes[$type])) {
                foreach ($dishes[$type] as $dish) {
                    $plat = $dishRepository->findOneBy(array("Name" => $dish["name"]));
                    if (!$plat) {
                        $plat = new Plat();
                        $plat->setName($dish["name"]);
                        $plat->setCategory($category);
                        $plat->setCalories($dish["calories"]);
                        $plat->setPrice($dish["price"]);
                        $plat->setImage($dish["image"]);
                        $plat->setDescription($dish["description"]);
                        $plat->setSticky($dish["sticky"]);
                        $plat->setUser($userRepository->findOneBy(array("Username" => $dish["users"])));

                        foreach ($dish['allergens'] as $allergenArray) {

                            $allergen = $allergenRepository->findOneBy(
                                array('Name' => $allergenArray)
                            );
                            if (!$allergen) {
                                $allergen = new Allergen();
                                $allergen->setName($allergenArray);
                                $em->persist($allergen);
                            }
                            $plat->addAllergen($allergen);
                        }
                        $em->persist($plat);
                        $em->flush();
                    }
                }
            }
        }
        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}