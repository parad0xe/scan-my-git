<?php

namespace App\Controller;

use App\Entity\Context;
use App\Service\GitRepositoryManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QuickAnalysisController extends AbstractController{

    #[Route('/quick/analysis', name: 'quick_analysis')]
    public function index(GitRepositoryManager $gitmanager): Response{
        $context= new Context();
        $context->setGithubUrl("https://github.com/parad0xe/scan-my-git.git");
        $context->setIsPrivate(true);
        $context->setSecretId("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");
        $url = $gitmanager->clone($context);
        return $this->render('quick_analysis/index.html.twig', [
            'controller_name' => 'QuickAnalysisController',
            'url' => $url
        ]);
    }
}
