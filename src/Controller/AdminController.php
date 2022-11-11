<?php

namespace App\Controller;

use App\Entity\Allergen;
use App\Entity\Category;
use App\Entity\Plat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_home', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    //créer une url de service admin/import-dishes qui se chargera d’insérer des plats et des allergènes au format json
    #[Route('/admin/import-dishes', name: 'admin_import_dishes', methods: ['GET'])]
    public function importDishes(EntitymanagerInterface $em): Response
    {
        $json = file_get_contents('../public/Json/toto.json');
        $dishes = json_decode($json, true);

        $dishRepository = $em->getRepository(Plat::class);
        $categoryRepository = $em->getRepository(Category::class);
        $allergenRepository = $em->getRepository(Allergen::class);

/*        //inserer les users
        foreach ($dishes as $dish) {
            $plat = new Plat();
            $plat->setUserId($dish['user_id']);
            $plat->setCategoryId($dish['category_id']);
            $em->persist($plat);*/

            foreach (["desserts", "entrees", "plats"] as $type) {
                $category = $categoryRepository->findOneBy(array("Name" => ucfirst($type)));

                if ($category && isset($dishes[$type])) {
                    foreach ($dishes[$type] as $dish) {
                        $plat = $dishRepository->findOneBy(array("Name" => $dish["name"]));
                        if (!$plat) {
                            $plat = new Plat();
                            $plat->setName($dish["name"]);
                            $plat->setCategoryId($category);
                            $plat->setCalories($dish["calories"]);
                            $plat->setPrice($dish["price"]);
                            $plat->setImage($dish["image"]);
                            $plat->setDescription($dish["description"]);

                        }
                        /*foreach ($dish["allergens"] as $allergenArray) {
                            // Update if exist, insert if not.
                            $allergen = $allergenRepository->findOneBy(array("Name" => $allergenArray["name"]));
                            if (!$allergen) {
                                $allergen = new Allergen();
                            }
                            $allergen->setName($allergenArray["name"]);
                            $allergen->setPlatId($plat);
/*                        }*/
                        $em->persist($plat);
                        $em->flush();
                    }
                }
            }
        //}
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}