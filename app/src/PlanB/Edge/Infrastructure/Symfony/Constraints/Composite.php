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

use PlanB\Edge\Infrastructure\Validator\Constraints\Constraint;
use Symfony\Component\Validator\Constraints\Composite as SymfonyComposite;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

abstract class Composite extends SymfonyComposite
{
    public ?array $constraints = [];

    public function __construct($options = null)
    {
        if (isset($options[$this->getCompositeOption()])) {
            throw new ConstraintDefinitionException(sprintf('You can\'t redefine the "%s" option. Use the "%s::getConstraints()" method instead.', $this->getCompositeOption(), __CLASS__));
        }

        $this->constraints = $this->getConstraints($options ?? []);

        parent::__construct($options);
    }

    final protected function getCompositeOption()
    {
        return 'constraints';
    }

    final public function validatedBy()
    {
        return CompositeValidator::class;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    abstract protected function getConstraints(array $options): array;
}
