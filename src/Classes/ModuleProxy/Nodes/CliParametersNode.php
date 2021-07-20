<?php

namespace App\Classes\ModuleProxy\Nodes;

use App\Classes\ModuleProxy\Nodes\Parameter\ParameterNode;

class CliParametersNode {
    private string $prefix;
    private string $executale_name;

    private string $value_separator;

    /** @var ParameterNode[] */
    private array $parameters;

    public function __construct(string $prefix, string $executable_name, array $parameters) {
        $this->prefix = $prefix;
        $this->executale_name = $executable_name;

        $this->value_separator = $parameters['value_separator'];

        $this->parameters = array_map(
            fn ($param_identifier) => new ParameterNode($param_identifier, $parameters['parameters'][$param_identifier]),
            array_keys($parameters['parameters'])
        );
    }

    public function getValueSeparator(): string {
        return $this->value_separator;
    }

    /**
     * @return ParameterNode[]
     */
    public function getParameters(): array {
        return $this->parameters;
    }

    public function extractValues(): array {
        return array_reduce($this->parameters, function (array $a, ParameterNode $parameterNode) {
            $a[$parameterNode->getIdentifier()] = $parameterNode->getValue();

            return $a;
        }, []);
    }

    public function bind(array $external_params) {
        foreach ($this->parameters as $parameter) {
            if (array_key_exists($parameter->getIdentifier(), $external_params)) {
                // TODO: Validate external data before set value (SECURITY BREACH: XSS)
                $parameter->setValue($external_params[$parameter->getIdentifier()]);
            }
        }
    }

    public function generateCommand(): string {
        $command = "$this->prefix $this->executale_name";

        $command .= ' '.implode(' ', array_map(function (ParameterNode $parameterNode): string {
            if (is_null($parameterNode->getValue()) || false === $parameterNode->getValue()) {
                return $parameterNode->getName();
            }
            if (is_null($parameterNode->getName()) || false === $parameterNode->getName()) {
                return $parameterNode->getValue();
            }

            return implode($this->getValueSeparator(), [$parameterNode->getName(), $parameterNode->getValue()]);
        }, $this->parameters));

        return trim($command, ' ');
    }
}
