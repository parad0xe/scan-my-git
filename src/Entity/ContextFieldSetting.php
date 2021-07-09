<?php

namespace App\Entity;

use App\Repository\ContextFieldSettingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContextFieldSettingRepository::class)
 */
class ContextFieldSetting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=ConfigurationField::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $configuration_field;

    /**
     * @ORM\ManyToOne(targetEntity=ModuleContextSetting::class, inversedBy="contextFieldSettings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $module_context_setting;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

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

    public function getModuleContextSetting(): ?ModuleContextSetting
    {
        return $this->module_context_setting;
    }

    public function setModuleContextSetting(?ModuleContextSetting $module_context_setting): self
    {
        $this->module_context_setting = $module_context_setting;

        return $this;
    }
}
