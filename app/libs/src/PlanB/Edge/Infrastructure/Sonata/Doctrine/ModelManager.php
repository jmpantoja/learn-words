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


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use PlanB\Edge\Application\UseCase\WriteCommandInterface;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Domain\Entity\EntityInterface;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager as SonataModelManager;

final class ModelManager extends SonataModelManager
{
    private ManagerCommandFactoryInterface $commandFactory;
    /**
     * @var DataPersisterInterface
     */
    private DataPersisterInterface $dataPersister;

    public function __construct(DataPersisterInterface $dataPersister, ManagerRegistry $registry)
    {

        $this->dataPersister = $dataPersister;
        parent::__construct($registry);

    }

//    public function find($class, $id)
//    {
//        if (is_a($class, EntityInterface::class, true)) {
//            return $this->getEntityManager($class)->getRepository($class)->findOneBy([
//                'id' => EntityId::fromString($id)
//            ]);
//        }
//        return parent::find($class, $id);
//    }

//    public function getModelIdentifier($class)
//    {
//        if (is_a($class, WriteCommandInterface::class, true)) {
//            return [
//                'id'
//            ];
//        }
//        return $this->getMetadata($class)->identifier;
//    }
//
//    public function getNormalizedIdentifier($entity)
//    {
//        if ($entity instanceof WriteCommandInterface) {
//            $entity = $entity->getEntity();
//        }
//
//        if ($entity instanceof EntityInterface) {
//            return $entity->getId();
//        }
//
//        return parent::getNormalizedIdentifier($entity);
//    }

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
        return $this->dataPersister->persist($command);
    }

    public function update($command)
    {
        return $this->dataPersister->persist($command);
    }

    public function delete($entity)
    {
        return $this->dataPersister->remove($entity);
    }
}
