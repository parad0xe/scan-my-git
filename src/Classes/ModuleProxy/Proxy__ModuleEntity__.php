<?php

namespace App\Classes\ModuleProxy;

use App\Classes\ModuleProxy\Nodes\CliParametersNode;
use App\Entity\Module;
use App\Exception\FileNotFoundException;
use App\Exception\MethodNotFoundException;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\NodeInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class Proxy__ModuleEntity__ {

    private string $prefix;
    private string $executable_name;
    private string $alias;
    private array $requirements;
    private CliParametersNode $cli_parameters;
    private FormBuilder $fb;

    /**
     * @throws FileNotFoundException
     */
    public function __construct(
        private Module $module
    ) {
        if (!file_exists($module->getDefinitionFile())) {
            throw new FileNotFoundException("The definition file ({$module->getDefinitionFile()}) does not exist");
        }

        $definition = (new Processor())->process(
            $this->getModuleDefinitionStructure(),
            Yaml::parseFile($module->getDefinitionFile())
        );

        if (!empty($definition['prefix'])) {
            $this->prefix = $definition['prefix'];
        } else {
            $this->prefix = '';
        }

        // $this->prefix = $definition['prefix'] ?? '';
        $this->executable_name = $definition['executable_name'];
        $this->alias = $definition['alias'];
        $this->requirements = $definition['requirements'] ?? [];

        $this->cli_parameters = new CliParametersNode(
            $module->getPath(),
            $this->prefix,
            "{$this->module->getPath()}/$this->executable_name",
            $definition['cli_parameters']
        );

        $this->fb = new FormBuilder($module->getId());
    }

    public function getModule(): Module {
        return $this->module;
    }

    public function getPrefix(): string {
        return $this->prefix;
    }

    public function getExecutableName(): string {
        return $this->executable_name;
    }

    public function getAlias(): string {
        return $this->alias;
    }

    public function getRequirements(): array {
        return $this->requirements;
    }

    public function getCliParameters(): CliParametersNode {
        return $this->cli_parameters;
    }

    public function getFb(): FormBuilder {
        return $this->fb;
    }

    /**
     * @throws MethodNotFoundException
     */
    public function __call(string $name, array $arguments): mixed {
        if (!method_exists($this->module, $name)) {
            throw new MethodNotFoundException("Module method ($name) not found");
        }

        return call_user_func_array([$this->module, $name], $arguments);
    }

    private function getModuleDefinitionStructure(): NodeInterface {
        // @formatter:off
        return (new TreeBuilder('module'))
            ->getRootNode()
                ->children()
                    ->scalarNode('prefix')->cannotBeEmpty()->end()
                    ->scalarNode('executable_name')->cannotBeEmpty()->isRequired()->end()
                    ->scalarNode('alias')->cannotBeEmpty()->isRequired()->end()
                    ->arrayNode('requirements')
                        ->requiresAtLeastOneElement()
                        ->scalarPrototype()->end()
                    ->end()
                    ->arrayNode('cli_parameters')
                        ->children()
                            ->scalarNode('value_separator')->isRequired()->cannotBeEmpty()->end()
                            ->arrayNode('parameters')
                            ->useAttributeAsKey('identifier')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('label')->isRequired()->cannotBeEmpty()->end()
                                    ->scalarNode('name')->isRequired()->cannotBeEmpty()->end()
                                    ->arrayNode('input')
                                        ->addDefaultsIfNotSet(false)
                                        ->children()
                                            ->enumNode('type')->values(['text', 'number', 'select'])->isRequired()->end()
                                            ->arrayNode('attributes')
                                                ->children()
                                                    ->scalarNode('maxlength')->cannotBeEmpty()->end()
                                                    ->scalarNode('minlength')->cannotBeEmpty()->end()
                                                    ->integerNode('min')->end()
                                                    ->integerNode('max')->end()
                                                ->end()
                                            ->end()
                                            ->arrayNode('select_configuration')
                                                ->children()
                                                    ->arrayNode('options')
                                                        ->requiresAtLeastOneElement()
                                                        ->scalarPrototype()->end()
                                                    ->end()
                                                    ->scalarNode('selected')->cannotBeEmpty()->isRequired()->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                    ->scalarNode('default')->defaultValue('')->cannotBeEmpty()->end()
                                    ->enumNode('required')->values([true, false])->isRequired()->defaultTrue()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->end()
            ->buildTree();
    }
}
