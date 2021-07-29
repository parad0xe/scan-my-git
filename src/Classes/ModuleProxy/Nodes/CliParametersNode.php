<?php

namespace App\Classes\ModuleProxy\Nodes;

use App\Classes\ModuleProxy\Nodes\Parameter\ParameterNode;
use App\Exception\IllegalArgumentException;

class CliParametersNode {
    private string $module_path;
    private string $prefix;
    private string $executable;

    private string $value_separator;
    /** @var ParameterNode[] */
    private array $parameters;

    public function __construct(string $module_path, string $prefix, string $executable, array $parameters) {
        $this->module_path = $module_path;
        $this->prefix = $prefix;
        $this->executable = $executable;

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

    /**
     * @throws IllegalArgumentException
     */
    public function bind(array $external_params) {
        foreach ($this->parameters as $parameter) {
            if (array_key_exists($parameter->getIdentifier(), $external_params)) {
                $parameter->setValue($external_params[$parameter->getIdentifier()]);
            }
        }
    }

    public function generateCommand(): string {
        $command = "$this->prefix $this->executable";

        $command .= ' '.implode(' ', array_map(/** * @throws IllegalArgumentException */ function (ParameterNode $parameterNode): string {
            preg_match("/^\\$\\{path:(.+)}$/", $parameterNode->getValue(), $matches);

            if(count($matches) > 0) {
                $parameterNode->setValue("{$this->module_path}/resource/$matches[1]");
            }

            if (is_null($parameterNode->getValue()) || false === $parameterNode->getValue()) {
                return escapeshellarg(trim($parameterNode->getName(), ' '));
            }

            if (is_null($parameterNode->getName()) || false === $parameterNode->getName()) {
                return escapeshellarg(trim($parameterNode->getValue(), ' '));
            }

            return trim(implode($this->getValueSeparator(), [$parameterNode->getName(), $parameterNode->getValue()]), ' ');
        }, $this->parameters));

        return escapeshellcmd(trim($command, ' '));
    }
}
