<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/analysis/{analysis_hash}')]
class AnalysisPipelineController extends AbstractController {

    #[Route('/initialize', name: 'analysis.initialize')]
    public function initialize(string $analysis_hash): JsonResponse {
        sleep(5);

        return $this->json([
            "success" => true
        ]);
    }

    #[Route('/run/{runner_id}', name: 'analysis.runner.run')]
    public function run(string $analysis_hash, int $runner_id): JsonResponse {
        sleep(5);

        return $this->json([
            "success" => true
        ]);
    }

    #[Route('/clean', name: 'analysis.clean')]
    public function clean(string $analysis_hash): JsonResponse {
        sleep(5);

        return $this->json([
            "success" => true
        ]);
    }
}
