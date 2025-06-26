<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    #[Route('/faq', name: 'app_main_faq')]
    public function faq(): Response
    {
        return $this->render('main/faq.html.twig');
    }
    #[Route('/mentions_legal', name: 'app_main_mentions')]
    public function mentions(): Response
    {
        return $this->render('main/mention_legal.html.twig');
    }
    #[Route('/cgv', name: 'app_main_cgv')]
    public function cgv(): Response
    {
        return $this->render('main/cgv.html.twig');
    }
    #[Route('/politique_confidentialite', name: 'app_main_politique')]
    public function politique(): Response
    {
        return $this->render('main/politique.html.twig');
    }

    #[Route('/ingredient', name: 'app_main_ingredient')]
    public function ingredient(): Response
    {
        return $this->render('main/ingredient.html.twig');
    }






}
