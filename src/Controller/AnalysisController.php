<?php

namespace App\Controller;

use App\Entity\Runner;
use App\Repository\AnalysisRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/analysis')]
class AnalysisController extends AbstractController {
    #[Route('/{analysis_id}', name: 'analysis.index', methods: ['GET'])]
    public function index(int $analysis_id): Response {
        return new Response();
    }

    #[Route('/{analysis_hash}/run', name: 'analysis.run', methods: ['GET'])]
    public function run(AnalysisRepository $analysisRepository, string $analysis_hash): Response {
        $analysis = $analysisRepository->findOneBy(['hash' => $analysis_hash]);
        $runners = $analysis->getRunners();
        $runners_id = array_map( fn( Runner $runner ) => $runner->getId(), $runners->toArray());
        // dd($analysis, $runners_id);
        return $this->render('analysis/run.html.twig', [
            'analysis'=>$analysis,
            'runners_id'=> $runners_id
        ]);
    }
    #[Route('/{analysis_hash}/result', name: 'analysis.result', methods: ['GET'])]
    public function result(AnalysisRepository $analysisRepository, string $analysis_hash): Response {
        $analysis = $analysisRepository->findOneBy(['hash' => $analysis_hash]);

        dd($analysis);
        return new Response();
    }

}