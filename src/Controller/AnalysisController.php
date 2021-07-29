<?php

namespace App\Controller;

use App\Classes\Errors\ErrorCode;
use App\Entity\Runner;
use App\Repository\AnalysisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/analysis')]
class AnalysisController extends AbstractController {

    #[Route('/{analysis_hash}/start', name: 'analysis.run', methods: ['GET'])]
    public function start(AnalysisRepository $analysisRepository, string $analysis_hash): Response {
        $analysis = $analysisRepository->findOneBy(['hash' => $analysis_hash]);

        if (!$analysis || $analysis->getStartedAt() !== null) {
            $this->addFlash("error", ErrorCode::ERROR_FORBIDDEN);
            return $this->redirectToRoute("context.index");
        }

        $runners = $analysis->getRunners();
        $runners_id = array_map(fn(Runner $runner) => $runner->getId(), $runners->toArray());

        return $this->render('analysis/run.html.twig', [
            'analysis' => $analysis,
            'runners_id' => $runners_id
        ]);
    }

    #[Route('/{analysis_hash}/result', name: 'analysis.result', methods: ['GET'])]
    public function result(AnalysisRepository $analysisRepository, string $analysis_hash): Response {
        $analysis = $analysisRepository->findOneBy(['hash' => $analysis_hash]);

        if (!$analysis) {
            $this->addFlash("error", ErrorCode::ERROR_FORBIDDEN);
            return $this->redirectToRoute("context.index");
        }

        dump($analysis->getRunners()->toArray());

        return $this->render('analysis/result.html.twig', [
            'analysis' => $analysis
        ]);
    }
}
