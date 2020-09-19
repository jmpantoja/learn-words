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
use PlanB\Edge\Domain\DataPersister\DataPersisterInterface;
use PlanB\Edge\Domain\PropertyExtractor\PropertyExtractor;
use ReflectionClass;
use RuntimeException;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager as SonataModelManager;
use Symfony\Component\Serializer\SerializerInterface;

final class ModelManager extends SonataModelManager
{
    /**
     * @var DataPersisterInterface
     */
    private DataPersisterInterface $dataPersister;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    public function __construct(DataPersisterInterface $dataPersister, ManagerRegistry $registry)
    {
        $this->dataPersister = $dataPersister;
        parent::__construct($registry);
    }

    public function getModelInstance($class)
    {
        $r = new ReflectionClass($class);
        if ($r->isAbstract()) {
            throw new RuntimeException(sprintf('Cannot initialize abstract class: %s', $class));
        }

        return $r->newInstanceWithoutConstructor();
    }


    public function getNormalizedIdentifier($entity)
    {
        return PropertyExtractor::fromObject($entity)->id();
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->dataPersister->persist($data);
    }

    /**
     * @param object $data
     * @return mixed
     */
    public function update($data)
    {
        return $this->dataPersister->persist($data);
    }

    /**
     * @param object $entity
     * @return mixed
     */
    public function delete($entity)
    {
        return $this->dataPersister->remove($entity);
    }
}
