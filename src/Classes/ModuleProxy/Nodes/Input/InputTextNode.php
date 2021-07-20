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
        parent::build($module_id, $attributes);

        return <<<HTML
            <div>
                {$this->getHTMLLabel()}
                {$this->getHTMLField($module_id)}
            </div>
        HTML;
    }
}
