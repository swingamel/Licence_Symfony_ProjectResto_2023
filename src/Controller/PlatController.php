<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Form\PlatType;
use App\Repository\PlatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlatController extends AbstractController
{
    #[Route('admin/dish', name: 'app_plat_index', methods: ['GET'])]
    public function index(PlatRepository $platRepository): Response
    {
        return $this->render('plat/index.html.twig', [
            'plats' => $platRepository->findAll(),
        ]);
    }

    #[Route('admin/dish/new', name: 'app_plat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlatRepository $platRepository): Response
    {
        $plat = new Plat();
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('Image')->getData();
            if ($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
                $plat->setImage($fileName);
            } else {
                $plat->setImage('http://via.placeholder.com/360x225');
            }
            $platRepository->save($plat, true);

            $this->addFlash('success', 'Le plat ' . $plat->getName() . ' a bien été ajouté');
            return $this->redirectToRoute('app_plat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('plat/new.html.twig', [
            'plat' => $plat,
            'form' => $form,
        ]);
    }

    #[Route('admin/dish/{id}', name: 'app_plat_show', methods: ['GET'])]
    public function show(Plat $plat): Response
    {
        return $this->render('plat/show.html.twig', [
            'plat' => $plat,
        ]);
    }

    #[Route('admin/dish/{id}/edit', name: 'app_plat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plat $plat, PlatRepository $platRepository): Response
    {
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $platRepository->save($plat, true);

            $this->addFlash('edit', 'Le plat ' . $plat->getName() . ' a bien été modifié');
            return $this->redirectToRoute('app_plat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('plat/edit.html.twig', [
            'plat' => $plat,
            'form' => $form,
        ]);
    }

    #[Route('admin/dish/{id}', name: 'app_plat_delete', methods: ['POST'])]
    public function delete(Request $request, Plat $plat, PlatRepository $platRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $plat->getId(), $request->request->get('_token'))) {
            $platRepository->remove($plat, true);
        }

        $this->addFlash('delete', 'Le plat ' . $plat->getName() . ' a bien été supprimé');
        return $this->redirectToRoute('app_plat_index', [], Response::HTTP_SEE_OTHER);
    }
}
