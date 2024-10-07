<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthorController extends AbstractController
{
    private $author;
    public function __construct()
    {
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william.jpg', 'username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );
        $this->author = $authors;
    }
    #[Route('/list', name: 'list_authors', methods: ['GET'])]
    public function listAuthors(): Response
    {
        return $this->render('author/list.html.twig', [
            'authors' => $this->author,
        ]);
    }


    #[Route('/show/{name}', name: "app_show", methods: ['GET'], defaults: ['name ' => 'you forgot to enter a name'])]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }
    #[Route('/author/{id}', name: 'author_details', methods: ['GET'])]
    public function authorDetails($id): Response
    {

        return $this->render('author/show.html.twig', [
            'authors' => $this->author,
            'id' => $id,
        ]);
    }
}
