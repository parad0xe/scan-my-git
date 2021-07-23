<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController {
    /**
     * @Route("/connect/github", name="github_connect")
     */
    public function connect(ClientRegistry $clientRegistry) {
        $client = $clientRegistry->getClient('github');

        return $client->redirect(['read:user', 'repo'], []);
    }

    /**
     * @Route("/connect/github/result", name="oauth_check")
     */
    public function check() {
        $username = $this->getUser()->getUsername();

        return $this->redirectToRoute('user_context', ['username' => $username]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout() {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');

        return $this->redirectToRoute('home');
    }
}
