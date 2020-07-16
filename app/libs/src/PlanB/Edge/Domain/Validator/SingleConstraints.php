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

namespace PlanB\Edge\Domain\Validator;

use Symfony\Component\Validator\Constraint;

final class SingleConstraints implements ConstraintsDefinition
{
    /**
     * @var Constraint[]
     */
    private array $constraints = [];

    /**
     * @inheritDoc
     */
    public function __construct(Constraint $constraint = null)
    {
        if ($constraint instanceof Constraint) {
            $this->constraints = [$constraint];
        }
    }

    public function add(Constraint $constraint): self
    {
        $this->constraints[] = $constraint;
        return $this;
    }

    public function getConstraints(): array
    {
        return $this->constraints;
    }
}
