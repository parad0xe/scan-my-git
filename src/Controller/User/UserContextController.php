<?php

namespace App\Controller\User;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user')]
class UserContextController extends AbstractController
{
    #[Route('/{username}/context', name: 'user_context')]
    public function index(Security $security): Response
    {
        $user = $this->getUser();
        $userContexts = $this->getUser()->getContexts();
        return $this->render('user/context/index.html.twig', [
            'user'=> $user,
            'userContexts'=>$userContexts
        ]);
    }
}
