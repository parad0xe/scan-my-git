<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModuleRepository::class)
 */
class Module {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=ModuleCategory::class, inversedBy="modules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

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

    public function getCategory(): ?ModuleCategory {
        return $this->category;
    }

    public function setCategory(?ModuleCategory $category): self {
        $this->category = $category;

        return $this;
    }

    public function getPath(): ?string {
        return realpath(__DIR__."/../../modules") . "/{$this->name}";
    }

    public function getDefinitionFile(): ?string {
        return "{$this->getPath()}/definition.yaml";
    }
}
