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

namespace PlanB\Edge\Infrastructure\Sonata\Doctrine;


use Doctrine\Persistence\ManagerRegistry;
use League\Tactician\CommandBus;
use PlanB\Edge\Application\UseCase\PersistenceCommandInterface;
use PlanB\Edge\Domain\Entity\EntityInterface;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager as SonataModelManager;

final class ModelManager extends SonataModelManager
{
    private CommandBus $commandBus;
    private ManagerCommandFactoryInterface $commandFactory;

    public function __construct(CommandBus $commandBus, ManagerRegistry $registry)
    {
        $this->commandBus = $commandBus;
        parent::__construct($registry);
    }

    public function find($class, $id)
    {
        if (is_a($class, EntityInterface::class, true)) {
            return $this->getEntityManager($class)->getRepository($class)->findOneBy([
                'id.uuid' => $id
            ]);
        }
        return parent::find($class, $id);
    }

    public function getModelIdentifier($class)
    {
        if (is_a($class, PersistenceCommandInterface::class, true)) {
            return [
                'id.id'
            ];
        }
        return $this->getMetadata($class)->identifier;
    }

    public function getNormalizedIdentifier($entity)
    {
        if ($entity instanceof PersistenceCommandInterface) {
            $entity = $entity->entity();
        }

        if ($entity instanceof EntityInterface) {
            return $entity->id();
        }

        return parent::getNormalizedIdentifier($entity);
    }

    /**
     * @param ManagerCommandFactoryInterface $commandFactory
     */
    public function setCommandFactory(ManagerCommandFactoryInterface $commandFactory)
    {
        $this->commandFactory = $commandFactory;
    }


    public function getModelInstance($class)
    {
        $r = new \ReflectionClass($class);
        if ($r->isAbstract()) {
            throw new \RuntimeException(sprintf('Cannot initialize abstract class: %s', $class));
        }

        return $r->newInstanceWithoutConstructor();
    }

    public function create($command)
    {
        return $this->commandBus->handle($command);
    }

    public function update($command)
    {
        return $this->commandBus->handle($command);
    }

    public function delete($entity)
    {
        $command = $this->commandFactory->deleteCommand($entity);
        return $this->commandBus->handle($command);
    }
}
