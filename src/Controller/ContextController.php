<?php

namespace App\Controller;

use App\Entity\Context;
use App\Entity\ContextModule;
use App\Repository\ContextRepository;
use App\Service\ModuleManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/context')]
class ContextController extends AbstractController {
    #[Route('/{context_id}', name: 'context.index', methods: ['GET'])]
    public function index(ModuleManager $moduleManager, ContextRepository $contextRepository, int $context_id): Response {
        $context = $contextRepository->find($context_id);
        $modules = $moduleManager->loadAll($context);

        return $this->render('context/index.html.twig', [
            'context' => $context,
            'modules' => $modules,
        ]);
    }

    #[Route('/create', name: 'context.create', methods: ['GET'])]
    public function create(EntityManagerInterface $entityManager): Response {
        $context = new Context();
        $context->setName('My Custom Context')
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

        $module->getCliParameters()->bind($data);

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
    public function quickAnalysis(Request $request, ModuleManager $moduleManager, EntityManagerInterface $entityManager): Response {
        $github_url = $request->get("github_url");

        if($github_url === null || empty($github_url)) {
            $this->addFlash("error", "Invalid URL");
            return $this->redirectToRoute("home", [], 400);
        }

        $context = (new Context())
            ->setName(sha1(uniqid()))
            ->setIsPrivate(false)
            ->setGithubUrl($github_url);
        $entityManager->persist($context);

        foreach (["php-security-checker"] as $module_name) {
            $module = $moduleManager->load(["name" => $module_name]);

            if(!$module) {
                continue;
            }

            $context_module = (new ContextModule())
                ->setContext($context)
                ->setModule($module->getModule())
                ->setCommand($module->getCliParameters()->generateCommand());
            $entityManager->persist($context_module);
        }

        $entityManager->flush();

        dd($context, $context_module);
    }
}
