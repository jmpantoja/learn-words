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


use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfigurator;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class CallToSetter
{
    private ContainerBuilder $container;
    private Definition $definition;
    private string $type;

    public static function form(ContainerBuilder $container, string $id): self
    {
        return new self($container, $id, FormConfigurator::TYPE);
    }

    private function __construct(ContainerBuilder $container, string $id, string $type)
    {
        $this->container = $container;
        $this->definition = $container->getDefinition($id);
        $this->type = $type;
    }

    /**
     * Aplica la llamada al setter correspondiente a la definición de un servicio que implementa AdminInterface
     */
    public function apply(): void
    {
        $reference = $this->reference();

        if (!$reference instanceof Reference) {
            return;
        }

        $methodName = $this->methodName();

        $this->definition->addMethodCall($methodName, [$reference]);
    }

    private function reference(): ?Reference
    {
        $admin = $this->definition->getClass();
        $service = self::configuratorServiceName($this->type, $admin);

        if (!$this->container->has($service)) {
            return null;
        }

        return new Reference($service);
    }

    private function methodName()
    {
        switch ($this->type) {
            case FormConfiguratorInterface::TYPE:
                return 'setFormConfigurator';

        }
    }

    /**
     * @param string $type
     * @param string $admin
     * @return string
     */
    public static function configuratorServiceName(string $type, string $admin): string
    {
        $admin = strtolower($admin);
        $admin = str_replace('\\', '.', $admin);

        return sprintf('planb.sonata.%s.configurator.%s', $type, $admin);
    }


}


