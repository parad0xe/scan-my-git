<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Message\RunnerMessage;
use App\Service\ModuleManager;
use App\Repository\RunnerRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/runner')]
class RunnerController extends AbstractController {
    #[Route('/run/{analysis_hash}/{runner_id}', name: 'runner.run', methods: ['POST'])]
    public function run(MessageBusInterface $bus, string $analysis_hash, int $runner_id): JsonResponse {
        $bus->dispatch(new RunnerMessage($runner_id));
        return new JsonResponse(['success'=> true]);
    }
}