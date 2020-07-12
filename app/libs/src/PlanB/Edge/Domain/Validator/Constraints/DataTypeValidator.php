<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PlanB\Edge\Domain\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\TypeValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class DataTypeValidator extends TypeValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof DataType) {
            throw new UnexpectedTypeException($constraint, DataType::class);
        }

        if (null === $value && false === $constraint->allowNull) {
            $types = (array)$constraint->type;
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setParameter('{{ type }}', implode('|', $types))
//                ->setCode(Type::INVALID_TYPE_ERROR)
                ->addViolation();

            return;
        }

        parent::validate($value, $constraint);
    }
}
