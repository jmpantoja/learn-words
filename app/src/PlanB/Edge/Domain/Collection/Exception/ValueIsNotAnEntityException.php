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

namespace PlanB\Edge\Domain\Collection\Exception;


use PlanB\Edge\Domain\Entity\EntityInterface;

final class ValueIsNotAnEntityException extends InvalidCollectionElement implements CollectionExceptionInterface
{
    /**
     * ValueIsNotAnEntityException constructor.
     * @param mixed $value
     */
    public function __construct($value)
    {
        parent::__construct($value, EntityInterface::class);
    }
}
