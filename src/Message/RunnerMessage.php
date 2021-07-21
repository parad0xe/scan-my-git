<?php
namespace App\Message;

class RunnerMessage
{

    public function __construct(
        private int $runner_id,
    ){   
    }

    public function getRunnerId(): int
    {
        return $this->runner_id;
    }
}