<?php

namespace App\Classes\ModuleProxy\Nodes\Input;

abstract class AbstractInputNode implements InputNodeInterface {
    protected string $type;
    protected array $attributes = [];

    /**
     * AbstractModuleInput constructor.
     *
     * @param string          $identifier
     * @param string          $label
     * @param string|int|null $value
     * @param bool            $required
     * @param array           $input_configuration
     */
    public function __construct(
        protected string $identifier,
        protected string $label,
        protected string | int | null $value,
        protected bool $required,
        protected array $input_configuration
    ) {
        $this->type = $input_configuration['type'];

        $this->attributes = (array_key_exists('attributes', $input_configuration))
            ? $input_configuration['attributes']
            : [];

        $this->attributes['class'] = $this->attributes['class'] ?? '';

        $this->initNode();
    }

    public function initNode() {
    }

    public function getHTMLLabel(): string {
        return "<label class='form-label'>$this->label</label>";
    }

    public function buildHTML(int $module_id, array $attributes = []): string {
        $this->attributes = array_merge($attributes, $this->attributes);

        return <<<HTML
            <div>
                {$this->getHTMLLabel()}
                {$this->getHTMLField($module_id)}
            </div>
        HTML;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getAttributes(): array {
        return $this->attributes;
    }

    public function setValue(int | string | null $value): InputNodeInterface {
        $this->value = $value;

        return $this;
    }

    public function stringifyAttributes(): string {
        return implode(
            ' ',
            array_map(fn ($k) => "$k={$this->attributes[$k]}", array_keys($this->attributes))
        );
    }
}
