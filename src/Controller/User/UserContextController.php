<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserContextController extends AbstractController {
    #[Route('/{username}/context', name: 'user_context')]
    public function index(): Response {
        $username = $this->getUser()->getUsername();
        $userContexts = $this->getUser()->getContexts();

        return $this->render('user/context/index.html.twig', [
            'username' => $username,
            'userContexts' => $userContexts,
        ]);
    }
}
