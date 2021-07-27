<?php

namespace App\Controller;

use App\Classes\Errors\ErrorCode;
use App\Entity\Analysis;
use App\Entity\Context;
use App\Entity\Runner;
use App\Exception\IllegalArgumentException;
use App\Repository\ContextRepository;
use App\Service\GitRepositoryManager;
use App\Service\ModuleManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
            $this->addFlash('error', ErrorCode::ERROR_FORBIDDEN);
            return $this->redirectToRoute('home');
        }

        $modules = $moduleManager->loadAll($context);

        return $this->render('context/index.html.twig', [
            'context' => $context,
            'modules' => $modules,
        ]);
    }

    #[Route('/{context_id}/module/attach/{module_id}', name: 'context.module.attach', methods: ['POST'])]
    public function attach(Request $request, ModuleManager $moduleManager, ContextRepository $contextRepository, int $context_id, int $module_id): Response {
        $context = $contextRepository->find($context_id);
        $module = $moduleManager->load($module_id);

        if (!$context || !$module) {
            $this->addFlash('error', ErrorCode::ERROR_FORBIDDEN);
            return $this->redirectToRoute('home');
        }

        $data = $request->request->get('module')[$module_id];

        try {
            $module->getCliParameters()->bind($data);
        } catch (IllegalArgumentException $e) {
            $this->logger->error($e->getMessage());
            $this->addFlash('error', ErrorCode::ERROR_INVALID_ARGUMENTS);

            return $this->redirectToRoute('context.index', ['context_id' => $context->getId()]);
        }

        $moduleManager->attach($context, $module);

        return $this->redirectToRoute('context.index', ['context_id' => $context->getId()]);
    }

    #[Route('/{context_id}/module/detach/{module_id}', name: 'context.module.detach', methods: ['GET'])]
    public function detach(ModuleManager $moduleManager, ContextRepository $contextRepository, int $context_id, int $module_id): Response {
        $context = $contextRepository->find($context_id);

        $module = $moduleManager->load($module_id);

        $moduleManager->detach($context, $module);

        return $this->redirectToRoute('context.index', ['context_id' => $context->getId()]);
    }

    #[Route('/quick-analysis', name: 'context.quick-analysis', methods: ['POST'])]
    public function quickAnalysis(Request $request, ModuleManager $moduleManager, EntityManagerInterface $entityManager, GitRepositoryManager $gitManager): Response {
        $github_url = $request->get('github_url');

        if (null === $github_url || empty($github_url)) {
            $this->addFlash('error', ErrorCode::ERROR_GIT_URL);
            return $this->redirectToRoute('home', []);
        }

        //region -- create context
        $context = (new Context())
            ->setName(sha1(uniqid()))
            ->setIsPrivate(false)
            ->setGithubUrl($github_url);
        $entityManager->persist($context);
        //endregion -- create context


        //region -- create analysis
        $analysis = (new Analysis())
            ->setContext($context);
        $entityManager->persist($analysis);
        //endregion -- create analysis

        $entityManager->flush();

        //region -- clone repository
        if (!$gitManager->clone($analysis)) {
            $this->addFlash("error", ErrorCode::ERROR_GIT_DOWNLOAD_FAILED);
            $entityManager->remove($context);
            $entityManager->remove($analysis);
            $entityManager->flush();

            return $this->redirectToRoute('home', []);
        }
        //endregion -- clone repository

        //region -- add modules
        foreach (['php-security-checker', 'phpstan'] as $module_name) {
            $module = $moduleManager->load(['name' => $module_name]);
            if ($module) {
                if ($gitManager->support($analysis, $module)) {
                    $moduleManager->attach($context, $module);
                }
            }
        }
        //endregion -- add modules

        if (empty($context->getContextModules())) {
            $this->logger->info($context->getGithubUrl() . " doesn't support any module.");
        }


        //region -- create runners
        foreach ($context->getContextModules() as $context_module) {
            $runner = (new Runner())
                ->setAnalysis($analysis)
                ->setContextModule($context_module);
            $entityManager->persist($runner);

            $analysis->addRunner($runner);
        }
        //endregion -- create runners

        $entityManager->flush();

        return $this->redirectToRoute('analysis.run', ['analysis_hash' => $analysis->getHash()]);
    }
}
