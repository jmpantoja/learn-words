<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PlanB\EdgeBundle\DependencyInjection\Compiler;

use PlanB\Edge\Infrastructure\Sonata\Configurator\ConfiguratorFactory;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Configura la injección de dependencias para las clases Admin de Sonata
 * Asigna diferentes configurators (Form, List, Filters, etc) via setter
 *
 * @package PlanB\EdgeBundle\DependencyInjection\Compiler
 */
final class SonataAdminCompiler implements CompilerPassInterface
{
    /**
     * @inheritDoc
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $this->addConfiguratorAliases($container);
        $this->addToConfiguratorSetters($container);
    }

    /**
     * @param ContainerBuilder $container
     * @return int|string
     */
    private function addConfiguratorAliases(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds('planb.admin.configurator') as $id => $tags) {
            $definition = $container->getDefinition($id);
            $alias = $this->configuratorAlias($definition, $tags);
            $aliases = $this->sanitizeAliases($id, $alias);

            $container->addAliases($aliases);
        }
    }

    private function sanitizeAliases(string $id, ?string $alias): array
    {
        if (is_null($alias)) {
            return [];
        }

        return [$alias => $id];
    }

    /**
     * @param Definition $definition
     * @param $tags
     * @return string
     */
    private function configuratorAlias(Definition $definition, $tags): ?string
    {
        $class = $definition->getClass();

        $type = $tags[0]['type'] ?? null;

        if (is_null($type)) {
            return null;
        }

        $admin = forward_static_call([$class, 'attachTo']);
        return $this->configuratorServiceName($type, $admin);

    }

    /**
     * @param ContainerBuilder $container
     */
    private function addToConfiguratorSetters(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds('planb.admin') as $id => $tags) {
            $definition = $container->getDefinition($id);
            $this->addToFormConfiguratorSetter($definition);
        }
    }

    /**
     * @param Definition $definition
     */
    private function addToFormConfiguratorSetter(Definition $definition): void
    {
        $type = FormConfiguratorInterface::TYPE;
        $admin = $definition->getClass();

        $service = $this->configuratorServiceName($type, $admin);
        $definition->addMethodCall('setFormConfigurator', [new Reference($service)]);
    }

    /**
     * @param string $type
     * @param string $admin
     * @return string
     */
    private function configuratorServiceName(string $type, string $admin): string
    {
        $admin = strtolower($admin);
        $admin = str_replace('\\', '.', $admin);

        return sprintf('planb.sonata.%s.configurator.%s', $type, $admin);
    }
}
