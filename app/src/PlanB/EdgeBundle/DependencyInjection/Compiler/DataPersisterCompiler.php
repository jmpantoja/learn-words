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


use PlanB\Edge\Domain\DataPersister\DataPersisterInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class DataPersisterCompiler implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $persisters = $container->findTaggedServiceIds('planb.data_persister');
        $service = $container->getDefinition(DataPersisterInterface::class);

        foreach ($persisters as $id => $tags) {

            $reference = $this->createReference($service, $id, $tags);
            $this->addPersister($service, $reference);
        }
    }

    /**
     * @param string $id
     * @param array $tags
     * @return ?Reference
     */
    private function createReference(Definition $service, string $id, array $tags): ?Reference
    {
        if (in_array($id, [$service->getClass(), DataPersisterInterface::class])) {
            return null;
        }
        return new Reference($id);
    }

    /**
     * @param Definition $service
     * @param Reference|null $reference
     */
    private function addPersister(Definition $service, ?Reference $reference): void
    {
        if (null === $reference) {
            return;
        }
        $service->addArgument($reference);
    }
}
