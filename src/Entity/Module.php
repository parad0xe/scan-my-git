<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModuleRepository::class)
 */
class Module
{
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
     * @ORM\ManyToOne(targetEntity=ModuleCategory::class, inversedBy="modules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity=ModuleConfiguration::class, mappedBy="module", cascade={"persist", "remove"})
     */
    private $moduleConfiguration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?ModuleCategory
    {
        return $this->category;
    }

    public function setCategory(?ModuleCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getModuleConfiguration(): ?ModuleConfiguration
    {
        return $this->moduleConfiguration;
    }

    public function setModuleConfiguration(ModuleConfiguration $moduleConfiguration): self
    {
        // set the owning side of the relation if necessary
        if ($moduleConfiguration->getModule() !== $this) {
            $moduleConfiguration->setModule($this);
        }

        $this->moduleConfiguration = $moduleConfiguration;

        return $this;
    }
}
