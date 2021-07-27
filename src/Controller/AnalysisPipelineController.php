<?php

namespace App\Controller;

use App\Classes\Errors\ErrorCode;
use App\Entity\Analysis;
use App\Repository\AnalysisRepository;
use App\Repository\RunnerRepository;
use App\Service\GitRepositoryManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/analysis/{analysis_hash}')]
class AnalysisPipelineController extends AbstractController {

    const MAX_PROCESS_TIMEOUT = 600;
    const SUCCESS_PROCESS = 'Succès';

    public function __construct(
        private LoggerInterface $logger,
        private HubInterface $hub
    ) {
    }

    #[Route('/initialize', name: 'analysis-pipeline.initialize')]
    public function initialize(GitRepositoryManager $gitManager, AnalysisRepository $analysisRepository, string $analysis_hash): Response {
        $analysis = $analysisRepository->findOneBy(['hash' => $analysis_hash]);

        if (!$analysis || !$gitManager->exist($analysis)) {
            return new JsonResponse(['success' => false, 'reason' => ErrorCode::ERROR_FORBIDDEN]);
        }

        $finder = new Finder();
        $finder->files()->name("composer.json")->in($gitManager->getPath($analysis));

        if ($finder->count() === 0) {
            $this->publishLog($analysis, "[INSTALL COMPOSER DEPENDENCIES] No dependencies to install");
        } else {
            $process = Process::fromShellCommandline("/usr/bin/composer install --ignore-platform-reqs --no-plugins | /bin/cat");

            try {
                $this->publishLog($analysis, "[INSTALL COMPOSER DEPENDENCIES] Started");

                $process->setWorkingDirectory($gitManager->getPath($analysis));
                $process->setTimeout(self::MAX_PROCESS_TIMEOUT);
                $process->setIdleTimeout(self::MAX_PROCESS_TIMEOUT);

                $process->mustRun(function ($type, $buffer) use ($analysis) {
                    $this->publishLog($analysis, $buffer);
                });

                $this->publishLog($analysis, "[INSTALL COMPOSER DEPENDENCIES] Finished");
            } catch (ProcessFailedException $e) {
                $this->logger->error($e->getMessage());
                return new JsonResponse(['success' => false, 'reason' => ErrorCode::ERROR_PROCESS_FAILED]);
            } catch (ProcessTimedOutException $e) {
                $this->logger->error($e->getMessage());
                return new JsonResponse(['success' => false, 'reason' => ErrorCode::ERROR_PROCESS_TIMEOUT]);
            }
        }

        return new JsonResponse(['success' => true, 'reason' => self::SUCCESS_PROCESS]);
    }

    #[Route('/run/{runner_id}', name: 'analysis-pipeline.runner.run')]
    public function run(EntityManagerInterface $entityManager, AnalysisRepository $analysisRepository, GitRepositoryManager $gitManager, RunnerRepository $runnerRepository, string $analysis_hash, int $runner_id): Response {
        $this->logger->debug("runner {$runner_id} started.");

        $analysis = $analysisRepository->findOneBy(['hash' => $analysis_hash]);


        if (!$analysis) {
            return new JsonResponse(['success' => false, 'reason' => ErrorCode::ERROR_FORBIDDEN]);
        }

        $runner = $runnerRepository->find($runner_id);

        if (!$runner) {
            return new JsonResponse(['success' => false, 'reason' => ErrorCode::ERROR_FORBIDDEN]);
        }

        if ($runner->getAnalysis()->getId() !== $analysis->getId()) {
            $this->logger->error('Match between analysis and runner failed.');
            return new JsonResponse(['success' => false, 'reason' => ErrorCode::ERROR_FORBIDDEN]);
        }

        $this->publishLog($analysis, "[RUNNER] {$runner->getContextModule()->getModule()->getName()} started");
        $cmd = $runner->getContextModule()->getCommand();

        $process = Process::fromShellCommandline($cmd . " | /bin/sed -r 's/\x1B[([0-9]{1,2}(;[0-9]{1,2})?)?[mGK]//g' | /bin/sed 's/\x0f//g' | /bin/cat");

        try {
            $process->setWorkingDirectory($gitManager->getPath($analysis));
            $process->setTimeout(self::MAX_PROCESS_TIMEOUT);
            $process->setIdleTimeout(self::MAX_PROCESS_TIMEOUT);

            $runner->setStartedAt(new \DateTimeImmutable());

            $process->mustRun();
        } catch (ProcessFailedException $e) {
            $this->logger->error($e->getMessage());
            return new JsonResponse(['success' => false, 'reason' => ErrorCode::ERROR_PROCESS_FAILED]);
        } catch (ProcessTimedOutException $e) {
            $this->logger->error($e->getMessage());
            return new JsonResponse(['success' => false, 'reason' => ErrorCode::ERROR_PROCESS_TIMEOUT]);
        }

        $runner->setFinishedAt(new \DateTimeImmutable());
        $runner->setOutput($process->getOutput());

        $entityManager->persist($runner);
        $entityManager->flush();

        $this->publishLog($analysis, "[RUNNER] {$runner->getContextModule()->getModule()->getName()} finished");

        $this->logger->debug("runner {$runner_id} finished.");

        return new JsonResponse(['success' => true, 'reason' => self::SUCCESS_PROCESS]);
    }

    #[Route('/clean', name: 'analysis-pipeline.clean')]
    public function clean(AnalysisRepository $analysisRepository, GitRepositoryManager $gitManager, string $analysis_hash): Response {

        $analysis = $analysisRepository->findOneBy(['hash' => $analysis_hash]);

        if (!$analysis) {
            return new JsonResponse(['success' => false, 'reason' => ErrorCode::ERROR_FORBIDDEN]);
        }

        $this->publishLog($analysis, "[CLEANING] Started");

        if (!$gitManager->delete($analysis)) {
            return new JsonResponse(['success' => false, 'reason' => ErrorCode::ERROR_INTERNAL]);
        }

        $this->publishLog($analysis, "[CLEANING] Finished");

        return new JsonResponse(['success' => true, 'reason' => self::SUCCESS_PROCESS]);
    }

    private function publishLog(Analysis $analysis, mixed $data) {
        $this->hub->publish(new Update(
            "{$analysis->getHash()}/logs",
            json_encode(['log' => $data])
        ));
    }
}
