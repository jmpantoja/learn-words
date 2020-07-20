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
use Sonata\DoctrineORMAdminBundle\Model\ModelManager as SonataModelManager;

final class ModelManager extends SonataModelManager
{
    /**
     * @var DataPersisterInterface
     */
    private DataPersisterInterface $dataPersister;

    public function __construct(DataPersisterInterface $dataPersister, ManagerRegistry $registry)
    {

        $this->dataPersister = $dataPersister;
        parent::__construct($registry);

    }

    public function getModelInstance($class)
    {
        $r = new \ReflectionClass($class);
        if ($r->isAbstract()) {
            throw new \RuntimeException(sprintf('Cannot initialize abstract class: %s', $class));
        }

        return $r->newInstanceWithoutConstructor();
    }

    /**
     * @param object $command
     * @return mixed
     */
    public function create($command)
    {
        return $this->dataPersister->persist($command);
    }

    /**
     * @param object $command
     * @return mixed
     */
    public function update($command)
    {
        return $this->dataPersister->persist($command);
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
