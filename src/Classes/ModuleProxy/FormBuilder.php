<?php

namespace App\Classes\ModuleProxy;

use App\Classes\ModuleProxy\Nodes\Parameter\ParameterNode;

class FormBuilder {
    public function __construct(private int $module_id) {
    }

    public function render(ParameterNode $parameterNode, array $attributes = []): string {
        if (!$parameterNode->hasInput()) {
            return '';
        }

        return $parameterNode->getInput()->buildHTML($this->module_id, $attributes);
    }
}
