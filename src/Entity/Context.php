<?php

namespace App\Entity;

use App\Repository\ContextRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContextRepository::class)
 */
class Context {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $github_url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $secret_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_private;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="contexts")
     * @ORM\JoinColumn(nullable=true)
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity=Analysis::class, mappedBy="context", orphanRemoval=true)
     */
    private $analyses;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    public function __construct() {
        $this->analyses = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getGithubUrl(): ?string {
        return $this->github_url;
    }

    public function setGithubUrl(string $github_url): self {
        $this->github_url = $github_url;

        return $this;
    }

    public function getIsPrivate(): ?bool {
        return $this->is_private;
    }

    public function setIsPrivate(bool $is_private): self {
        $this->is_private = $is_private;

        return $this;
    }

    public function getSecretId(): ?string {
        return $this->secret_id;
    }

    public function setSecretId(?string $secret_id): self {
        $this->secret_id = $secret_id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self {
        $this->created_at = $created_at;

        return $this;
    }

    public function getOwner(): ?User {
        return $this->owner;
    }

    public function setOwner(?User $owner): self {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Analysis[]
     */
    public function getAnalyses(): Collection {
        return $this->analyses;
    }

    public function addAnalysis(Analysis $analysis): self {
        if (!$this->analyses->contains($analysis)) {
            $this->analyses[] = $analysis;
            $analysis->setContext($this);
        }

        return $this;
    }

    public function removeAnalysis(Analysis $analysis): self {
        if ($this->analyses->removeElement($analysis)) {
            // set the owning side to null (unless already changed)
            if ($analysis->getContext() === $this) {
                $analysis->setContext(null);
            }
        }

        return $this;
    }
}
