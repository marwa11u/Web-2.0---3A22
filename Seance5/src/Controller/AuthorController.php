<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/author')]
class AuthorController extends AbstractController
{
    #[Route('/show', name: 'app_showList')]
    public function showList(AuthorRepository $authorRepo): Response
    {
        $authors = $authorRepo->findAll();
        return $this->render('author/showList.html.twig', ['authors' => $authors]);
    }

    #[Route('/addStatic', name: "app_addStatic")]
    public function addAuthorStatic(ManagerRegistry $doctrine): Response
    {
        $author = new Author();
        $author->setUserName('Marwa');
        $author->setEmail('marwa@gmail.com');

        $em = $doctrine->getManager();
        $em->persist($author); //commit
        $em->flush(); //push

        return $this->redirectToRoute('app_showList');
    }
    #[Route('/add', name: "app_add")]
    public function addAuthor(Request $request, EntityManagerInterface $entityManager): Response
    {

        $author = new Author();

        // Create du form
        $form = $this->createForm(AuthorType::class, $author);

        // Gestion de la requête du formulaire
        $form->handleRequest($request);

        // Vérification si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement de l'auteur dans la base de données
            $entityManager->persist($author);
            $entityManager->flush();

            // Redirection vers la liste des auteurs
            return $this->redirectToRoute('app_showList');
        }

        // Rendu du formulaire dans le template Twig
        return $this->render('author/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete(Author $author, EntityManagerInterface $entityManager): RedirectResponse
    {

        $entityManager->remove($author);
        $entityManager->flush();

        $this->addFlash('success', 'L\'auteur a été supprimé avec succès.');

        return $this->redirectToRoute('app_showList');
    }


    #[Route('/edit/{id}', name: 'app_edit')]
    public function edit(Request $request, Author $author, EntityManagerInterface $entityManager): Response
    {
        // Créer le formulaire pour l'auteur existant
        $form = $this->createForm(AuthorType::class, $author);

        // Gérer la requête du formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour les informations de l'auteur dans la base de données
            $entityManager->flush();

            // Rediriger vers la liste des auteurs
            return $this->redirectToRoute('app_showList');
        }

        // Rendre le formulaire dans le template Twig
        return $this->render('author/edit.html.twig', [
            'form' => $form->createView(),
            'author' => $author,
        ]);
    }
}
