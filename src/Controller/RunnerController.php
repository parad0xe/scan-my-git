<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Service\ModuleManager;
use App\Repository\RunnerRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/runner')]
class RunnerController extends AbstractController {
    #[Route('/run/{analysis_hash}/{runner_id}', name: 'runner.run', methods: ['POST'])]
    public function run(LoggerInterface $logger, string $analysis_hash, int $runner_id): JsonResponse {
        $logger->info("runner {$runner_id} started.");
        sleep(5);
        $logger->info("runner {$runner_id} finished.");
        return new JsonResponse(['success'=> true]);
    }
}