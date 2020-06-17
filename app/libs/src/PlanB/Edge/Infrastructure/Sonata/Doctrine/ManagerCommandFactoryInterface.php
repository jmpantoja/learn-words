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


use PlanB\Edge\Application\UseCase\EntityCommandInterface;
use PlanB\Edge\Domain\Entity\EntityInterface;

interface ManagerCommandFactoryInterface
{
    public function updateCommand(EntityInterface $entity): EntityCommandInterface;

    public function createCommand(EntityInterface $entity): EntityCommandInterface;

    public function deleteCommand(EntityInterface $entity): EntityCommandInterface;
}
