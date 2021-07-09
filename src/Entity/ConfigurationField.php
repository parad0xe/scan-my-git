<?php

namespace App\Entity;

use App\Repository\ConfigurationFieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfigurationFieldRepository::class)
 */
class ConfigurationField
{
    const TYPE_TEXT="text";
    const TYPE_CHECKBOX="checkbox";
    const TYPE_SELECT="select";
    const TYPE_NONE="none";
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $required;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $label;

    /**
     * @ORM\Column(type="array")
     */
    private $type = [];

    /**
     * @ORM\Column(type="array")
     */
    private $options = [];

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $alias;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $default_value;

    /**
     * @ORM\OneToMany(targetEntity=FieldDependency::class, mappedBy="configuration_field", orphanRemoval=true)
     */
    private $fieldDependencies;

    /**
     * @ORM\ManyToOne(targetEntity=ModuleConfiguration::class, inversedBy="configurationFields")
     * @ORM\JoinColumn(nullable=false)
     */
    private $module_configuration;

    public function __construct()
    {
        $this->fieldDependencies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequired(): ?bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getType(): ?array
    {
        return $this->type;
    }

    public function setType(array $type): self
    {
        if(!in_array($type, [self::TYPE_TEXT, self::TYPE_CHECKBOX, self::TYPE_SELECT, self::TYPE_NONE]))
            throw new \InvalidArgumentException("unexpected variable type");
        
        $this->type = $type;
        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function setOptions(?array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getDefaultValue(): ?string
    {
        return $this->default_value;
    }

    public function setDefaultValue(?string $default_value): self
    {
        $this->default_value = $default_value;

        return $this;
    }

    /**
     * @return Collection|FieldDependency[]
     */
    public function getFieldDependencies(): Collection
    {
        return $this->fieldDependencies;
    }

    public function addFieldDependency(FieldDependency $fieldDependency): self
    {
        if (!$this->fieldDependencies->contains($fieldDependency)) {
            $this->fieldDependencies[] = $fieldDependency;
            $fieldDependency->setConfigurationField($this);
        }

        return $this;
    }

    public function removeFieldDependency(FieldDependency $fieldDependency): self
    {
        if ($this->fieldDependencies->removeElement($fieldDependency)) {
            // set the owning side to null (unless already changed)
            if ($fieldDependency->getConfigurationField() === $this) {
                $fieldDependency->setConfigurationField(null);
            }
        }

        return $this;
    }

    public function getModuleConfiguration(): ?ModuleConfiguration
    {
        return $this->module_configuration;
    }

    public function setModuleConfiguration(?ModuleConfiguration $module_configuration): self
    {
        $this->module_configuration = $module_configuration;

        return $this;
    }
}
