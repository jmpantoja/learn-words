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


use PlanB\Edge\Application\UseCase\WriteCommandInterface;
use PlanB\Edge\Domain\Entity\EntityInterface;

interface ManagerCommandFactoryInterface
{
    public function saveCommand(array $input, ?EntityInterface $entity): WriteCommandInterface;

    public function deleteCommand(EntityInterface $entity): WriteCommandInterface;
}
