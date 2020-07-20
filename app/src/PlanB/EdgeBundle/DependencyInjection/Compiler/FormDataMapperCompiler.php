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
use PlanB\Edge\Infrastructure\Symfony\Form\CompositeDataMapper;
use PlanB\Edge\Infrastructure\Symfony\Form\CompositeDataMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\SingleDataMapper;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Configura la injección de dependencias para las clases Admin de Sonata
 * Asigna diferentes configurators (Form, List, Filters, etc) via setter
 *
 * @package PlanB\EdgeBundle\DependencyInjection\Compiler
 */
final class FormDataMapperCompiler implements CompilerPassInterface
{
    /**
     * @inheritDoc
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $this->processComposite($container);
        $this->processSingle($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processComposite(ContainerBuilder $container): void
    {
        $dataMapper = $container->getDefinition(CompositeDataMapper::class);

        foreach ($container->findTaggedServiceIds('planb.composite_form_type') as $id => $tags) {
            $definition = $container->getDefinition($id);
            $definition->addMethodCall('setDataMapper', [$dataMapper]);
        }
    }

    private function processSingle(ContainerBuilder $container): void
    {
        $dataMapper = $container->getDefinition(SingleDataMapper::class);

        foreach ($container->findTaggedServiceIds('planb.single_form_type') as $id => $tags) {
            $definition = $container->getDefinition($id);
            $definition->addMethodCall('setDataMapper', [$dataMapper]);
        }
    }


}
