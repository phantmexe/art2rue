<?php

namespace App\Controller;

use App\Entity\Oeuvre;
use App\Form\OeuvreType;
use App\Repository\OeuvreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/oeuvre')]
final class OeuvreController extends AbstractController
{
    #[Route(name: 'app_oeuvre_index', methods: ['GET'])]
    public function index(OeuvreRepository $oeuvreRepository): Response
    {
        return $this->render('oeuvre/index.html.twig', [
            'oeuvres' => $oeuvreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_oeuvre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $oeuvre = new Oeuvre();
        $form = $this->createForm(OeuvreType::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload de l'image
            $photoFile = $form->get('nomFichier')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gestion de l'erreur d'upload si besoin
                }

                $oeuvre->setNomFichier($newFilename);
            }

            $entityManager->persist($oeuvre);
            $entityManager->flush();

            return $this->redirectToRoute('app_oeuvre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('oeuvre/new.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_oeuvre_show', methods: ['GET'])]
    public function show(Oeuvre $oeuvre): Response
    {
        return $this->render('oeuvre/show.html.twig', [
            'oeuvre' => $oeuvre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_oeuvre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Oeuvre $oeuvre, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(OeuvreType::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('nomFichier')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gestion de l'erreur
                }

                $oeuvre->setNomFichier($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_oeuvre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('oeuvre/edit.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_oeuvre_delete', methods: ['POST'])]
    public function delete(Request $request, Oeuvre $oeuvre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$oeuvre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($oeuvre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_oeuvre_index', [], Response::HTTP_SEE_OTHER);
    }
}
