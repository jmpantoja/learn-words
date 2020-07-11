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
use PlanB\Edge\Application\UseCase\WriteCommandInterface;
use PlanB\Edge\Domain\Entity\EntityInterface;
use Ramsey\Uuid\Uuid;
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
                'id.uuid' => Uuid::fromString($id)
            ]);
        }
        return parent::find($class, $id);
    }

    public function getModelIdentifier($class)
    {
        if (is_a($class, WriteCommandInterface::class, true)) {
            return [
                'id.id'
            ];
        }
        return $this->getMetadata($class)->identifier;
    }

    public function getNormalizedIdentifier($entity)
    {
        if ($entity instanceof WriteCommandInterface) {
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
        return $this->write($command);
    }

    public function update($command)
    {
        return $this->write($command);
    }

    private function write(WriteCommandInterface $command): EntityInterface
    {
        $this->commandBus->handle($command);
        return $command->entity();
    }

    public function delete($entity)
    {
        $command = $this->commandFactory->deleteCommand($entity);
        return $this->commandBus->handle($command);
    }
}
