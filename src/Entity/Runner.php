<?php

namespace App\Entity;

use App\Repository\RunnerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RunnerRepository::class)
 */
class Runner {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $output;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $started_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $finished_at;

    /**
     * @ORM\ManyToOne(targetEntity=Analysis::class, inversedBy="runners")
     * @ORM\JoinColumn(nullable=false)
     */
    private $analysis;

    /**
     * @ORM\ManyToOne(targetEntity=ContextModule::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $context_module;

    public function getId(): ?int {
        return $this->id;
    }

    public function getOutput(): ?string {
        return $this->output;
    }

    public function setOutput(string $output): self {
        $this->output = $output;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable {
        return $this->started_at;
    }

    public function setStartedAt(\DateTimeImmutable $started_at): self {
        $this->started_at = $started_at;

        return $this;
    }

    public function getFinishedAt(): ?\DateTimeImmutable {
        return $this->finished_at;
    }

    public function setFinishedAt(\DateTimeImmutable $finished_at): self {
        $this->finished_at = $finished_at;

        return $this;
    }

    public function getAnalysis(): ?Analysis {
        return $this->analysis;
    }

    public function setAnalysis(?Analysis $analysis): self {
        $this->analysis = $analysis;

        return $this;
    }

    public function getContextModule(): ?ContextModule {
        return $this->context_module;
    }

    public function setContextModule(?ContextModule $context_module): self {
        $this->context_module = $context_module;

        return $this;
    }
}
