<?php

namespace App\Controller\Admin;

use App\Entity\Chambre;
use App\Form\ChambreType;
use App\Repository\ChambreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/chambre')]
class ChambreController extends AbstractController
{
    #[Route('/', name: 'app_chambre_index', methods: ['GET'])]
    public function index(ChambreRepository $chambreRepository): Response
    {
        return $this->render('chambre/index.html.twig', [
            'chambres' => $chambreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chambre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ChambreRepository $chambreRepository): Response
    {
        $chambre = new Chambre();
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //____________________________
            // début code ajouter image
            // je récupére l'image du formulaire 
            $image = $form->get('image')->getData();
            if (!is_null($image)) {
                // création d'un nom unique pour l'image
                $new_name_image = uniqid() . '.' . $image->guessExtension();
                // envoi de l'image  dans le dossier "images" sur le server 
                $image->move(
                    // premier param le chemin 
                    $this->getParameter('upload_dir'),
                    // le second param, le new name de l'image
                    $new_name_image
                );
                $chambre->setImage($new_name_image);
            } else {
                $chambre->setImage("defaultImage.jpeg");
            }
            // fin code ajouter image
            //____________________________
            $chambreRepository->save($chambre, true);

            return $this->redirectToRoute('app_chambre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chambre/new.html.twig', [
            'chambre' => $chambre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chambre_show', methods: ['GET'])]
    public function show(Chambre $chambre): Response
    {
        return $this->render('chambre/show.html.twig', [
            'chambre' => $chambre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chambre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chambre $chambre, ChambreRepository $chambreRepository): Response
    {
        $old_name_image = $chambre->getImage();
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //____________________________
            //debut code modif image
            $image = $form->get('image')->getData();
            if (!is_null($image)) {
                // création d'un nom unique pour l'image
                $new_name_image = uniqid() . '.' . $image->guessExtension();
                // envoi de l'image dans le dossier "images" sur le server 
                $image->move(
                    // premier param le chemin 
                    $this->getParameter('upload_dir'),
                    // le second param, le new name de l'image
                    $new_name_image
                );
                $chambre->setImage($new_name_image);
            } else {
                $chambre->setImage($old_name_image);
            }
            // fin code modif image
            //____________________________
            $chambreRepository->save($chambre, true);

            return $this->redirectToRoute('app_chambre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chambre/edit.html.twig', [
            'chambre' => $chambre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chambre_delete', methods: ['POST'])]
    public function delete(Request $request, Chambre $chambre, ChambreRepository $chambreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chambre->getId(), $request->request->get('_token'))) {
            $chambreRepository->remove($chambre, true);
        }

        return $this->redirectToRoute('app_chambre_index', [], Response::HTTP_SEE_OTHER);
    }
}
