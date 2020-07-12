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


use PlanB\Edge\Shared\Validator\Cons;
use Symfony\Component\Validator\Constraint;

final class ConstraintsFactory
{
    private ConstraintsDefinition $definition;

    public function __construct()
    {
        $this->definition = new SingleConstraints();
    }

    public function composite(): CompositeConstraints
    {
        $this->definition = new CompositeConstraints();
        return $this->definition;
    }

    public function single(): SingleConstraints
    {
        $this->definition = new SingleConstraints();
        return $this->definition;
    }

    /**
     * @return Constraint[]
     */
    public function constraints(): array
    {
        return $this->definition->constraints();
    }
}
