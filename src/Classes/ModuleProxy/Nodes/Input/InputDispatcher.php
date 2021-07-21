<?php

namespace App\Classes\ModuleProxy\Nodes\Input;

use App\Classes\ModuleProxy\Nodes\Parameter\ParameterNode;

class InputDispatcher {
    public static function dispatch(ParameterNode $parameterNode, array $data): ?InputNodeInterface {
        if (0 === count($data)) {
            return null;
        }

        return match ($data['type']) {
            'text', 'number' => new InputTextNode(
                $parameterNode->getIdentifier(),
                $parameterNode->getLabel(),
                $parameterNode->getValue(),
                $parameterNode->isRequired(),
                $data
            ),
            'select' => new InputSelectNode(
                $parameterNode->getIdentifier(),
                $parameterNode->getLabel(),
                $parameterNode->getValue(),
                $parameterNode->isRequired(),
                $data
            ),
            default => null,
        };
    }
}
