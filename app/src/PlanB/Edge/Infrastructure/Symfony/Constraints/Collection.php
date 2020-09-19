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

namespace PlanB\Edge\Infrastructure\Symfony\Constraints;

use Symfony\Component\Validator\Constraints\Collection as SymfonyCollection;

abstract class Collection extends SymfonyCollection
{
    public function __construct()
    {
        $options = [
            'fields' => $this->getConstraints()
        ];

        $this->allowExtraFields = true;
        parent::__construct($options);
    }

    /**
     * @inheritDoc
     */
    public function validatedBy()
    {
        return CollectionValidator::class;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    abstract public function ignoreWhen($value): bool;

    abstract protected function getConstraints(): array;
}
