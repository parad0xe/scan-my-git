<?php

namespace App\Classes\ModuleProxy\Nodes\Input;

class InputTextNode extends AbstractInputNode {
    public function initNode() {
        parent::initNode();
        $this->attributes['class'] .= ' form-control';
    }

    public function getHTMLField(int $module_id): string {
        return <<<HTML
            <input 
                name='module[$module_id][$this->identifier]' 
                type='$this->type' 
                {$this->stringifyAttributes()} 
                required='$this->required' 
                value='$this->value' />
        HTML;
    }

    public function build(int $module_id, array $attributes = []): string {
        parent::buildHTML($module_id, $attributes);

        return <<<HTML
            <div>
                {$this->getHTMLLabel()}
                {$this->getHTMLField($module_id)}
            </div>
        HTML;
    }

    public function validateValue(int | string | null $value): bool {
        if (is_int($value)) {
            if (
                (array_key_exists('min', $this->attributes) && $value < $this->attributes['min']) ||
                (array_key_exists('max', $this->attributes) && $value < $this->attributes['max'])
            ) {
                return false;
            }

            return true;
        }

        if (is_string($value)) {
            if (
                (array_key_exists('minlength', $this->attributes) && mb_strlen($value) < $this->attributes['minlength']) ||
                (array_key_exists('maxlength', $this->attributes) && mb_strlen($value) > $this->attributes['maxlength'])
            ) {
                return false;
            }

            return true;
        }

        return true;
    }
}
