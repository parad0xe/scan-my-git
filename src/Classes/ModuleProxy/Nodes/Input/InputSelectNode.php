<?php

namespace App\Classes\ModuleProxy\Nodes\Input;

class InputSelectNode extends AbstractInputNode {
    private array $options;
    private string | int $selected;

    public function initNode() {
        parent::initNode();
        $this->options = $this->input_configuration['select_configuration']['options'];
        $this->selected = $this->input_configuration['select_configuration']['selected'];
    }

    public function getHTMLField(int $module_id): string {
        $this->attributes['class'] .= ' form-select';

        $select = <<<HTML
            <select 
                name='module[{$module_id}][$this->identifier]' 
                {$this->stringifyAttributes()} 
                required='$this->required'>
        HTML;

        foreach ($this->options as $option) {
            if ($this->value) {
                $selected = ($option === $this->value) ? "selected='selected'" : '';
            } else {
                $selected = ($option === $this->selected) ? "selected='selected'" : '';
            }
            $select .= "<option $selected value='$option'>$option</option>";
        }

        $select .= '</select>';

        return <<<HTML
            $select
        HTML;
    }

    public function buildHTML(int $module_id, array $attributes = []): string {
        parent::buildHTML($module_id, $attributes);

        return <<<HTML
            <div>
                {$this->getHTMLLabel()}
                {$this->getHTMLField($module_id)}
            </div>
        HTML;
    }

    public function validateValue(int | string | null $value): bool {
        return in_array($value, $this->options);
    }
}
