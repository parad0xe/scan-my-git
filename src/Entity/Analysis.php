<?php

namespace App\Entity;

use App\Repository\AnalysisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnalysisRepository::class)
 */
class Analysis {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $started_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $finished_at;

    /**
     * @ORM\ManyToOne(targetEntity=Context::class, inversedBy="analyses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $context;

    /**
     * @ORM\OneToMany(targetEntity=Runner::class, mappedBy="analysis", orphanRemoval=true)
     */
    private $runners;

    /**
     * @ORM\Column(type="text")
     */
    private $hash;

    public function __construct() {
        $this->runners = new ArrayCollection();
        $this->hash = hash("sha256", uniqid('', true));
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getScore(): ?int {
        return $this->score;
    }

    public function setScore(int $score): self {
        $this->score = $score;

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

    public function getContext(): ?Context {
        return $this->context;
    }

    public function setContext(?Context $context): self {
        $this->context = $context;

        return $this;
    }

    /**
     * @return Collection|Runner[]
     */
    public function getRunners(): Collection {
        return $this->runners;
    }

    public function addRunner(Runner $runner): self {
        if (!$this->runners->contains($runner)) {
            $this->runners[] = $runner;
            $runner->setAnalysis($this);
        }

        return $this;
    }

    public function removeRunner(Runner $runner): self {
        if ($this->runners->removeElement($runner)) {
            // set the owning side to null (unless already changed)
            if ($runner->getAnalysis() === $this) {
                $runner->setAnalysis(null);
            }
        }

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }
}
