<?php

namespace App\Service;

use App\Classes\ModuleProxy\Proxy__ModuleEntity__;
use App\Classes\Utils;
use App\Entity\Analysis;
use App\Entity\Context;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;

class GitRepositoryManager {

    private string $targetDirectory = '/var/www/html/downloads/github/';
    private array $validHosts = ['github.com'];

    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function clone(Analysis $analysis): bool {
        $context = $analysis->getContext();
        $filesystem = new Filesystem();

        //test if context is valid

        if (!$this->isValid($context)) return false;

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
            // $process->setIdleTimeout(10);

            $process->mustRun();
        } catch (ProcessFailedException | ProcessTimedOutException $e) {
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

    public function delete(Analysis $analysis): bool {
        //remove from the folder
        $path = $this->getPath($analysis);

        Utils::rrmdir($path);

        return true;
    }

    public function exist(Analysis $analysis): bool {
        $path = $this->getPath($analysis);
        return (new Filesystem())->exists($path);
    }

    public function isValid(Context $context): bool {
        if (!in_array(parse_url($context->getGithubUrl(), PHP_URL_HOST), $this->validHosts)) {
            return false;
        }

        return true;
    }

    public function getPath(Analysis $analysis): string {
        $dirName = sha1($analysis->getId());
        return $this->targetDirectory . $dirName;
    }

    public function support(Analysis $analysis, Proxy__ModuleEntity__ $proxy): bool {
        $finder = new Finder();
        $path = $this->getPath($analysis);

        $reqs = $proxy->getRequirements();

        foreach ($reqs as $req) {
            $finder->files()->name($req)->in($path);
            if ($finder->count() === 0) {
                return false;
            }
        }
        return true;
    }
}
