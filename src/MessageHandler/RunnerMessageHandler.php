<?php
namespace App\MessageHandler;

use Psr\Log\LoggerInterface;
use App\Message\RunnerMessage;
use App\Repository\RunnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RunnerMessageHandler implements MessageHandlerInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private RunnerRepository $runnerRepository,
        private LoggerInterface $logger
    ){
    }

    public function __invoke(RunnerMessage $message){
        $this->logger->info("runner {$message->getRunnerId()} started.");
        sleep(5);
        $this->logger->info("runner {$message->getRunnerId()} finished.");
    }
}