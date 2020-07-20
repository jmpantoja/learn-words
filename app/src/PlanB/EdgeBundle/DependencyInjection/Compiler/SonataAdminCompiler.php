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
use PlanB\Edge\Infrastructure\Sonata\Doctrine\ModelManager;
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
    public function process(ContainerBuilder $container): void
    {
        $this->addConfiguratorAliases($container);
        $this->addToConfiguratorSetters($container);
    }

    /**
     * @param ContainerBuilder $container
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

    /**
     * @param string $id
     * @param string|null $alias
     * @return array|string[]
     */
    private function sanitizeAliases(string $id, ?string $alias): array
    {
        if (is_null($alias)) {
            return [];
        }

        return [$alias => $id];
    }

    /**
     * @param Definition $definition
     * @param string[][] $tags
     * @return string|null
     */
    private function configuratorAlias(Definition $definition, array $tags): ?string
    {
        $class = $definition->getClass();

        $type = $tags[0]['type'] ?? null;

        if (is_null($type)) {
            return null;
        }

        $admin = forward_static_call([$class, 'attachTo']);
        return CallToSetter::configuratorServiceName($type, $admin);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addToConfiguratorSetters(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds('planb.admin') as $id => $tags) {
            CallToSetter::form($container, $id)
                ->apply();

            CallToSetter::dataGrid($container, $id)
                ->apply();

        }
    }

}
