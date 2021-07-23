<?php

namespace App\Controller;

use App\Entity\Runner;
use App\Entity\Context;
use App\Entity\Analysis;
use App\Exception\IllegalArgumentException;
use App\Repository\ContextRepository;
use App\Service\ModuleManager;
use App\Service\GitRepositoryManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/context')]
class ContextController extends AbstractController {
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    #[Route('/{context_id}', name: 'context.index', requirements: ['context_id' => '\d+'], methods: ['GET'])]
    public function index(ModuleManager $moduleManager, ContextRepository $contextRepository, int $context_id): Response {
        $context = $contextRepository->find($context_id);

        if (!$context) {
            $this->addFlash('error', 'Aucun contexte associé');

            return $this->redirectToRoute('home');
        }

        $modules = $moduleManager->loadAll($context);

        return $this->render('context/index.html.twig', [
            'context' => $context,
            'modules' => $modules,
        ]);
    }

    #[Route('/create', name: 'context.create', methods: ['GET'])]
    public function create(EntityManagerInterface $entityManager): Response {
        $context = (new Context())
            ->setName('My Custom Context')
            ->setGithubUrl('https://github.com')
            ->setIsPrivate(false);

        $entityManager->persist($context);
        $entityManager->flush();

        return $this->redirectToRoute('context.index', ['context_id' => $context->getId()]);
    }

    #[Route('/{context_id}/module/attach/{module_id}', name: 'context.module.attach', methods: ['POST'])]
    public function attach(Request $request, ModuleManager $moduleManager, ContextRepository $contextRepository, int $context_id, int $module_id): Response {
        $context = $contextRepository->find($context_id);
        $module = $moduleManager->load($module_id);

        $data = $request->request->get('module')[$module_id];

        try {
            $module->getCliParameters()->bind($data);
        } catch (IllegalArgumentException $e) {
            $this->logger->error($e->getMessage());
            $this->addFlash('error', 'Argument invalide');

            return $this->redirectToRoute('context.index', ['context_id' => $context->getId()]);
        }

        $moduleManager->attach($context, $module);

        return $this->redirectToRoute('context.index', ['context_id' => $context->getId()]);
//        return new JsonResponse(["success" => true]);
    }

    #[Route('/{context_id}/module/detach/{module_id}', name: 'context.module.detach', methods: ['GET'])]
    public function detach(ModuleManager $moduleManager, ContextRepository $contextRepository, int $context_id, int $module_id): Response {
        $context = $contextRepository->find($context_id);

        $module = $moduleManager->load($module_id);

        $moduleManager->detach($context, $module);

        return $this->redirectToRoute('context.index', ['context_id' => $context->getId()]);
//        return new JsonResponse(["success" => true]);
    }

    #[Route('/quick-analysis', name: 'context.quick-analysis', methods: ['POST'])]
    public function quickAnalysis(Request $request, ModuleManager $moduleManager, EntityManagerInterface $entityManager, GitRepositoryManager $gitManager): Response {
        $github_url = $request->get('github_url');

        if (null === $github_url || empty($github_url)) {
            $this->addFlash('error', 'Invalid URL');

            return $this->redirectToRoute('home', []);
        }

        //create context
        $context = (new Context())
            ->setName(sha1(uniqid()))
            ->setIsPrivate(false)
            ->setGithubUrl($github_url);
        $entityManager->persist($context);
        

        
        //create analysis
        $analysis = (new Analysis())
        ->setContext($context);
        
        $entityManager->persist($analysis);
        $entityManager->flush();

        //clone repo
        if(!$gitManager->clone($analysis)){
            $this->addFlash("error", "erreur lors du téléchargement du repository");
            $entityManager->remove($context);
            $entityManager->remove($analysis);
            $entityManager->flush();
            return $this->redirectToRoute('home', []);
        }

        foreach (['php-security-checker', 'phpstan'] as $module_name) {
            $module = $moduleManager->load(['name' => $module_name]);
            if ($module) {
                if($gitManager->support($analysis, $module)){
                    $moduleManager->attach($context, $module);
                }
            }
        }

        //create runners
        foreach ($context->getContextModules() as $context_module) {
            $runner = (new Runner())
            ->setAnalysis($analysis)
            ->setContextModule($context_module);
            $entityManager->persist($runner);

            
            $analysis->addRunner($runner);
        }
        $entityManager->flush();
        
        //redirect
        return $this->redirectToRoute('analysis.run', ['analysis_hash'=> $analysis->getHash()]);
    }

    #[Route('/{context_id}/delete', name: 'context.delete')]
    public function delete(EntityManagerInterface $entityManager, ContextRepository $contextRepository, int $context_id): JsonResponse {
        $context = $contextRepository->find($context_id);
        if(!$context) return new JsonResponse(['response' => 'context doesnt exist']);
        $entityManager->remove($context);
        $entityManager->flush();
        return new JsonResponse(['response' => 'context removed']);
    }
}
