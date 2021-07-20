<?php

namespace App\Classes\ModuleProxy;

use App\Classes\ModuleProxy\Nodes\CliParametersNode;
use App\Entity\Module;
use Exception;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\NodeInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class Proxy__ModuleEntity__ {
    private string $prefix;
    private string $executable_name;
    private string $alias;
    private CliParametersNode $cli_parameters;
    private FormBuilder $fb;

    public function __construct(
        private Module $module
    ) {
        $definition = (new Processor())->process(
            $this->getModuleDefinitionStructure(),
            Yaml::parseFile($module->getDefinitionFile())
        );

        $this->prefix = $definition['prefix'] ?? '';
        $this->executable_name = $definition['executable_name'];
        $this->alias = $definition['alias'];

        $this->cli_parameters = new CliParametersNode(
            $this->prefix,
            $this->executable_name,
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

    public function getCliParameters(): CliParametersNode {
        return $this->cli_parameters;
    }

    public function getFb(): FormBuilder {
        return $this->fb;
    }

    /**
     * @throws Exception
     */
    public function __call(string $name, array $arguments): mixed {
        if (!method_exists($this->module, $name)) {
            // TODO: Add MethodNotFoundException
            throw new Exception("Method $name Not Found");
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
