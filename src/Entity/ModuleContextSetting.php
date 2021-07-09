<?php

namespace App\Entity;

use App\Repository\ModuleContextSettingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModuleContextSettingRepository::class)
 */
class ModuleContextSetting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Module::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $module;

    /**
     * @ORM\OneToMany(targetEntity=ContextFieldSetting::class, mappedBy="module_context_setting", orphanRemoval=true)
     */
    private $contextFieldSettings;

    public function __construct()
    {
        $this->contextFieldSettings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }

    /**
     * @return Collection|ContextFieldSetting[]
     */
    public function getContextFieldSettings(): Collection
    {
        return $this->contextFieldSettings;
    }

    public function addContextFieldSetting(ContextFieldSetting $contextFieldSetting): self
    {
        if (!$this->contextFieldSettings->contains($contextFieldSetting)) {
            $this->contextFieldSettings[] = $contextFieldSetting;
            $contextFieldSetting->setModuleContextSetting($this);
        }

        return $this;
    }

    public function removeContextFieldSetting(ContextFieldSetting $contextFieldSetting): self
    {
        if ($this->contextFieldSettings->removeElement($contextFieldSetting)) {
            // set the owning side to null (unless already changed)
            if ($contextFieldSetting->getModuleContextSetting() === $this) {
                $contextFieldSetting->setModuleContextSetting(null);
            }
        }

        return $this;
    }
}
