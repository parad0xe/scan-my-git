<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {
    #[Route('/', name: 'home')]
    public function index(): Response {
        return $this->render('home/index.html.twig');
    }
    #[Route('/github', name: 'github')]
    public function github(): Response {
        return $this->render('github/index.html.twig');
    }
}
