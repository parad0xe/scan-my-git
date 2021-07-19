<?php
namespace App\Service;

use App\Entity\Context;
use App\Exception\GitException;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;


class GitRepositoryManager {
    private $targetDirectory = "/var/www/html/downloads/github/";
    private $validHosts = ["github.com"];

    public function clone( Context $context){
        $filesystem = new Filesystem();

        //test if context is valid
        $this->isValid($context);

        $DirName = sha1($context->getGithubUrl().$context->getId());

        $url = $context->getGithubUrl();

        //if repo is private
        if($context->getIsPrivate()){
            $scheme = parse_url($url, PHP_URL_SCHEME);
            $path = parse_url($url, PHP_URL_PATH);
            $host = parse_url($url, PHP_URL_HOST);
            $secret=$context->getSecretId();
            $url = "$scheme://$secret@$host$path";
        }

        //create directory
        $filesystem->mkdir($this->targetDirectory.$DirName, 0666);
        if(!$filesystem->exists($this->targetDirectory.$DirName)){
            throw new GitException();
        }

        //clone
        $process = new Process(['git', 'clone', $url, $this->targetDirectory.$DirName]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new GitException();
        }

        $filesystem->chmod( $this->targetDirectory.$DirName, 0666, 0000, true);
    }
    public function delete(Context $context){
        //remove from the folder
        $filesystem = new Filesystem();
        $DirName = sha1($context->getGithubUrl().$context->getId());
        $filesystem->remove($this->targetDirectory.$DirName);
        
        if($filesystem->exists($this->targetDirectory.$DirName)){
            throw new GitException();
        }
    }
    public function isValid(Context $context){
        if(!in_array(parse_url($context->getGithubUrl(), PHP_URL_HOST),$this->validHosts)){
            throw new GitException();
        }
        return true;
    }

}