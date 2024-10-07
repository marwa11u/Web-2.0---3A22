<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Entity\Author;
use App\Form\LivreType;
use App\Repository\AuthorRepository;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RedirectResponse;


#[Route('/livre')]
class LivreController extends AbstractController
{

    #[Route('/show', name: 'app_showList')]
    public function showList(LivreRepository $livRepo): Response
    {
        $livre = $livRepo->findAll();
        return $this->render('livre/author_livres.html.twig', ['livres' => $livre]);
    }

    #[Route('/author/{id}/listBook', name: 'app_listBookAuthor')]
    public function ListBookAuthor(LivreRepository $livreRepo, AuthorRepository $authorRepo, int $id): Response
    {
        $author = $authorRepo->find($id);

        if (!$author) {
            throw $this->createNotFoundException('Author not found');
        }

        $livres = $livreRepo->findBy(['author' => $author]);
        return $this->render('livre/author_livres.html.twig', [
            'livres' => $livres,
            'author' => $author
        ]);
    }


    #[Route('/addStatic', name: "app_addStatic")]
    public function addLivreStatic(ManagerRegistry $doctrine, LivreRepository $livRepo): Response
    {
        $author = new Author();

        $author = $livRepo->findById(2);
        $livre = new Livre();
        $livre->setRef(441);
        $livre->setTitle('titre1');
        $livre->setNbrPage(11);
        $livre->setAuthor($author);
        $livre->setPicture('https');

        $em = $doctrine->getManager();
        $em->persist($livre); //commit
        $em->flush(); //push

        return $this->redirectToRoute('app_showList');
    }


    #[Route('/add', name: "app_add")]
    public function addLivre(Request $request, EntityManagerInterface $entityManager): Response
    {

        $livre = new Livre();

        $form = $this->createForm(LivreType::class, $livre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livre);
            $entityManager->flush();

            return $this->redirectToRoute('app_showList');
        }

        return $this->render('livre/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete(Livre $livre, EntityManagerInterface $entityManager): RedirectResponse
    {

        $entityManager->remove($livre);
        $entityManager->flush();

        $this->addFlash('success', 'L\'auteur a été supprimé avec succès.');

        return $this->redirectToRoute('app_showList');
    }


    #[Route('/edit/{id}', name: 'app_edit')]
    public function edit(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreType::class, $livre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_showList');
        }

        // Rendre le formulaire dans le template Twig
        return $this->render('livre/edit.html.twig', [
            'form' => $form->createView(),
            'livre' => $livre,
        ]);
    }
}
