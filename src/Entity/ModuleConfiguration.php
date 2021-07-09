<?php

namespace App\Entity;

use App\Repository\ModuleConfigurationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModuleConfigurationRepository::class)
 */
class ModuleConfiguration
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=ConfigurationField::class, mappedBy="module_configuration_id", orphanRemoval=true)
     */
    private $configurationFields;

    /**
     * @ORM\OneToOne(targetEntity=Module::class, inversedBy="moduleConfiguration", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $module;

    public function __construct()
    {
        $this->configurationFields = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|ConfigurationField[]
     */
    public function getConfigurationFields(): Collection
    {
        return $this->configurationFields;
    }

    public function addConfigurationField(ConfigurationField $configurationField): self
    {
        if (!$this->configurationFields->contains($configurationField)) {
            $this->configurationFields[] = $configurationField;
            $configurationField->setModuleConfigurationId($this);
        }

        return $this;
    }

    public function removeConfigurationField(ConfigurationField $configurationField): self
    {
        if ($this->configurationFields->removeElement($configurationField)) {
            // set the owning side to null (unless already changed)
            if ($configurationField->getModuleConfigurationId() === $this) {
                $configurationField->setModuleConfigurationId(null);
            }
        }

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(Module $module): self
    {
        $this->module = $module;

        return $this;
    }
}
