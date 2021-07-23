<?php

namespace App\Service;

use App\Entity\Context;
use App\Entity\Analysis;
use Psr\Log\LoggerInterface;
use App\Exception\GitException;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use App\Classes\ModuleProxy\Proxy__ModuleEntity__;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class GitRepositoryManager {
    private $targetDirectory = '/var/www/html/downloads/github/';
    private $validHosts = ['github.com'];

    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function clone(Analysis $analysis): bool {
        $context = $analysis->getContext();
        $filesystem = new Filesystem();

        //test if context is valid
        
        if(!$this->isValid($context)) return false;

        $path = $this->getPath($analysis);

        $url = $context->getGithubUrl();

        //if repo is private
        if ($context->getIsPrivate()) {
            $scheme = parse_url($url, PHP_URL_SCHEME);
            $path = parse_url($url, PHP_URL_PATH);
            $host = parse_url($url, PHP_URL_HOST);
            $token = $context->getSecretId();
            // $token = $context->getUser()->getGithubToken();
            $url = "$scheme://$token@{$host}{$path}";
        }

        //create directory
        try {
            $filesystem->mkdir($path, 0666);
        } catch (IOException $e) {
            $this->logger->error($e->getMessage());
            return false;
        }

        //clone
        $process = new Process(['git', 'clone', $url, $path]);
        try {
            $process->setTimeout(60);
            $process->setIdleTimeout(10);

            $process->mustRun();
        } catch (ProcessFailedException $e) {
            $this->logger->error($e->getMessage());
            $this->delete($analysis);
            return false;
        } catch(ProcessTimedOutException $e){
            $this->logger->error($e->getMessage());
            $this->delete($analysis);
            return false;
        }

        //change rights
        // try {
        //     $filesystem->chmod($this->targetDirectory.$DirName, 0666, 0000, true);
        // } catch (IOException $e) {
        //     $this->logger->warning($e->getMessage());
        // }

        return true;
    }

    public function delete(Analysis $analysis) {
        //remove from the folder
        $filesystem = new Filesystem();
        $path = $this->getPath($analysis);
        
        try {
            $filesystem->remove($path);
        } catch (IOException $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
    }

    public function isValid(Context $context) {
        if (!in_array(parse_url($context->getGithubUrl(), PHP_URL_HOST), $this->validHosts)) {
            return false;
        }

        return true;
    }

    public function getPath(Analysis $analysis): string {
        $dirName = sha1($analysis->getId());
        return $this->targetDirectory.$dirName;
    }

    public function support(Analysis $analysis, Proxy__ModuleEntity__ $proxy): bool {
        $path = $this->getPath($analysis);

        $reqs = $proxy->getRequirements();
        foreach($reqs as $k=>$req){
            $reqs[$k] = '-name '+$req;
        };
        $command = array_merge(['find', '.'], $reqs);

        $process = new Process($command);
        try {
            $process->setTimeout(60);
            $process->setIdleTimeout(10);

            $process->mustRun();
        } catch (ProcessFailedException $e) {
            $this->logger->error($e->getMessage());
            $this->delete($analysis);
            return false;
        } catch(ProcessTimedOutException $e){
            $this->logger->error($e->getMessage());
            $this->delete($analysis);
            return false;
        }

        return true;
    }
}
