<?php

namespace App\Entity;

use App\Repository\FieldDependencyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FieldDependencyRepository::class)
 */
class FieldDependency
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $on_active;

    /**
     * @ORM\ManyToOne(targetEntity=ConfigurationField::class, inversedBy="fieldDependencies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $configuration_field;

    /**
     * @ORM\ManyToOne(targetEntity=ConfigurationField::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent_configuration_field;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOnActive(): ?bool
    {
        return $this->on_active;
    }

    public function setOnActive(bool $on_active): self
    {
        $this->on_active = $on_active;

        return $this;
    }

    public function getConfigurationField(): ?ConfigurationField
    {
        return $this->configuration_field;
    }

    public function setConfigurationField(?ConfigurationField $configuration_field): self
    {
        $this->configuration_field = $configuration_field;

        return $this;
    }

    public function getParentConfigurationField(): ?ConfigurationField
    {
        return $this->parent_configuration_field;
    }

    public function setParentConfigurationField(?ConfigurationField $parent_configuration_field): self
    {
        $this->parent_configuration_field = $parent_configuration_field;

        return $this;
    }
}
