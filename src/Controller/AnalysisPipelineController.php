<?php

namespace App\Controller;

use App\Repository\AnalysisRepository;
use App\Repository\RunnerRepository;
use App\Service\GitRepositoryManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/analysis/{analysis_hash}')]
class AnalysisPipelineController extends AbstractController {

    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    #[Route('/initialize', name: 'analysis-pipeline.initialize')]
    public function initialize(GitRepositoryManager $gitManager, AnalysisRepository $analysisRepository, string $analysis_hash): Response {
        $analysis = $analysisRepository->findOneBy(['hash' => $analysis_hash]);
        if (!$analysis) {
            return new JsonResponse(['success' => false, 'reason' => 'Forbidden']);
        }

        $process = Process::fromShellCommandline("npm install | echo && composer install | echo",);
        try {
            $process->setWorkingDirectory($gitManager->getPath($analysis));
            $process->setTimeout(60);
            $process->setIdleTimeout(60);

            $process->mustRun();
        } catch (ProcessFailedException $e) {
            $this->logger->error($e->getMessage());

            return new JsonResponse(['success' => false, 'reason' => "Process failed {$e->getCode()}"]);
        } catch (ProcessTimedOutException $e) {
            $this->logger->error($e->getMessage());

            return new JsonResponse(['success' => false, 'reason' => 'Time out']);
        }

        return new JsonResponse(['success' => true, 'reason' => 'Success']);
    }

    #[Route('/run/{runner_id}', name: 'analysis-pipeline.runner.run')]
    public function run(EntityManagerInterface $entityManager, AnalysisRepository $analysisRepository, GitRepositoryManager $gitManager, RunnerRepository $runnerRepository, string $analysis_hash, int $runner_id): Response {
        $this->logger->info("runner {$runner_id} started.");

        $analysis = $analysisRepository->findOneBy(['hash' => $analysis_hash]);
        if (!$analysis) {
            return new JsonResponse(['success' => false, 'reason' => 'Forbidden']);
        }

        $runner = $runnerRepository->find($runner_id);
        if (!$runner) {
            return new JsonResponse(['success' => false, 'reason' => 'Forbidden']);
        }

        if ($runner->getAnalysis()->getId() !== $analysis->getId()) {
            $this->logger->error('Match between analysis and runner failed.');

            return new JsonResponse(['success' => false, 'reason' => 'Forbidden']);
        }
        $cmd = $runner->getContextModule()->getCommand();

        $process = Process::fromShellCommandline($cmd);
        try {
            $process->setWorkingDirectory($gitManager->getPath($analysis));
            $process->setTimeout(60);
            $process->setIdleTimeout(60);

            $runner->setStartedAt(new \DateTimeImmutable());
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            $this->logger->error($e->getMessage());
            return new JsonResponse(['success' => false, 'reason' => "Process failed {$e->getCode()}"]);
        } catch (ProcessTimedOutException $e) {
            $this->logger->error($e->getMessage());

            return new JsonResponse(['success' => false, 'reason' => 'Time out']);
        }
        $runner->setFinishedAt(new \DateTimeImmutable());
        $runner->setOutput($process->getOutput());

        $entityManager->persist($runner);
        $entityManager->flush();

        $this->logger->info("runner {$runner_id} finished.");

        return new JsonResponse(['success' => true, 'reason' => 'Successfully Finished']);
    }

    #[Route('/clean', name: 'analysis-pipeline.clean')]
    public function clean(AnalysisRepository $analysisRepository, GitRepositoryManager $gitManager, string $analysis_hash): Response {
        $analysis = $analysisRepository->findOneBy(['hash' => $analysis_hash]);
        if (!$analysis) {
            return new JsonResponse(['success' => false, 'reason' => 'Forbidden']);
        }

        if (!$gitManager->delete($analysis)) {
            return new JsonResponse(['success' => false, 'reason' => 'Error occurred']);
        }
        return new JsonResponse(['success' => true, 'reason' => 'Successfully Finished']);
    }
}
