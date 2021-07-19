<?php
namespace App\Service;

use App\Entity\Context;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


class GitRepositoryManager {
    private $targetDirectory = "/var/www/html/downloads/github/";
    private $validHosts = ["github.com"];

    public function clone( Context $context){
        //test if context is valid
        $this->isValid($context);

        $DirName = sha1($context->getGithubUrl().$context->getId());
        // if directory already exist
        if(is_dir($DirName)){
            $this->delete($context);
        }
        $url = $context->getGithubUrl();
        if($context->getIsPrivate()){
            $scheme = parse_url($url, PHP_URL_SCHEME);
            $path = parse_url($url, PHP_URL_PATH);
            $host = parse_url($url, PHP_URL_HOST);
            $secret=$context->getSecretId();
            $url = "$scheme://$secret@$host$path";
        }
        $process = new Process(['mkdir', '-p', $this->targetDirectory.$DirName]);
        $process->run();
        $process = new Process(['git', 'clone', $url, $this->targetDirectory.$DirName]);
        $process->run();
        $process = new Process(['chmod', '-R', '666', $this->targetDirectory.$DirName]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
    public function delete(Context $context){
        //remove from the folder
        $DirName = sha1($context->getGithubUrl().$context->getId());
        $process = new Process(['rm', '-r', $this->targetDirectory.$DirName]);
        $process->run();
    }
    public function isValid(Context $context){
        if(!in_array(parse_url($context->getGithubUrl(), PHP_URL_HOST),$this->validHosts)){
            // throw Exception;
            return false;
        }
        return true;
    }

}