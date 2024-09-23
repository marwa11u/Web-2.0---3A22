<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
//use Symfony\Component\Routing\Annotation\Route;

//
class HomeController extends AbstractController
{
    #[Route('/home', name: 'home_index')] //route 
    public function index(): Response //class response : type de retour (requete http)
    {
        //   return new Response('<h1><i>Hello World!</h1>');
        return $this->render('service/index.html.twig'); //redirection
    }

    #[Route('/contact', name: 'contact_index')] //route 
    public function contact(): Response //class response : type de retour (requete http)
    {

        //   return new Response('<h1><i>Hello World!</h1>');
        return $this->render('service/showService.html.twig'); //redirection
    }

    #[Route('/', name: 'go_to_index')]
    public function goToIndex(): Response
    {
        return $this->redirectToRoute('home_index');
    }
}
