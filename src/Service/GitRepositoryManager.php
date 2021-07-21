<?php

namespace App\Service;

use App\Entity\Context;
use Psr\Log\LoggerInterface;
use App\Exception\GitException;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
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

    public function clone(Context $context): bool {
        $filesystem = new Filesystem();

        //test if context is valid
        
        if(!$this->isValid($context)) return false;

        $DirName = sha1($context->getGithubUrl().$context->getId());

        $url = $context->getGithubUrl();

        //if repo is private
        if ($context->getIsPrivate()) {
            $scheme = parse_url($url, PHP_URL_SCHEME);
            $path = parse_url($url, PHP_URL_PATH);
            $host = parse_url($url, PHP_URL_HOST);
            $secret = $context->getSecretId();
            $url = "$scheme://$secret@{$host}{$path}";
        }

        //create directory
        try {
            $filesystem->mkdir($this->targetDirectory.$DirName, 0666);
        } catch (IOException $e) {
            $this->logger->error($e->getMessage());
            return false;
        }

        //clone
        $process = new Process(['git', 'clone', $url, $this->targetDirectory.$DirName]);
        try {
            $process->setTimeout(10);
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            $this->logger->error($e->getMessage());
            return false;
        } catch(ProcessTimedOutException $e){
            $this->logger->error($e->getMessage());
            return false;
        }

        //change rights
        try {
            $filesystem->chmod($this->targetDirectory.$DirName, 0666, 0000, true);
        } catch (IOException $e) {
            $this->logger->warning($e->getMessage());
        }

        return true;
    }

    public function delete(Context $context) {
        //remove from the folder
        $filesystem = new Filesystem();
        $DirName = sha1($context->getGithubUrl().$context->getId());
        
        try {
            $filesystem->remove($this->targetDirectory.$DirName);
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

    public function getPath(Context $context) : string {
        $DirName = sha1($context->getGithubUrl().$context->getId());
        return $this->targetDirectory.$DirName;
    }
}
