<?php

namespace App\Entity;

use App\Repository\ContextModuleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContextModuleRepository::class)
 */
class ContextModule {

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
     * @ORM\Column(type="text")
     */
    private $command;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $parameters = [];

    /**
     * @ORM\ManyToOne(targetEntity=Context::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $context;

    public function __construct() { }

    public function getId(): ?int {
        return $this->id;
    }

    public function getModule(): ?Module {
        return $this->module;
    }

    public function setModule(?Module $module): self {
        $this->module = $module;

        return $this;
    }

    public function getCommand(): ?string
    {
        return $this->command;
    }

    public function setCommand(string $command): self
    {
        $this->command = $command;

        return $this;
    }

    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    public function setParameters(?array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getContext(): ?Context
    {
        return $this->context;
    }

    public function setContext(?Context $context): self
    {
        $this->context = $context;

        return $this;
    }
}
