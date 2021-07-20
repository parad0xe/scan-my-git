<?php

namespace App\Classes\ModuleProxy\Nodes\Parameter;

use App\Classes\ModuleProxy\Nodes\Input\InputDispatcher;
use App\Classes\ModuleProxy\Nodes\Input\InputNodeInterface;

class ParameterNode {
    private string $identifier;
    private mixed $label;
    private mixed $name;
    private mixed $default;
    private mixed $is_required;
    private mixed $value;

    private ?InputNodeInterface $input;

    public function __construct(string $identifier, array $data) {
        $this->identifier = $identifier;

        $this->label = $data['label'];
        $this->name = $data['name'];
        $this->default = $data['default'];
        $this->is_required = $data['required'];
        $this->value = $this->default;

        $this->input = InputDispatcher::dispatch($this, $data['input']);
    }

    public function getIdentifier(): string {
        return $this->identifier;
    }

    public function getLabel(): string {
        return $this->label;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function getInput(): ?InputNodeInterface {
        return $this->input;
    }

    public function getDefault(): null | int | string {
        return $this->default;
    }

    public function isRequired(): bool {
        return $this->is_required;
    }

    public function hasInput(): bool {
        return null !== $this->input;
    }

    public function getValue(): int | string | null {
        return $this->value;
    }

    /**
     * @param int|string|null $value
     *
     * @return $this
     */
    public function setValue(int | string | null $value): self {
        $this->value = $value;
        if ($this->input) {
            $this->input->setValue($value);
        }

        return $this;
    }
}
