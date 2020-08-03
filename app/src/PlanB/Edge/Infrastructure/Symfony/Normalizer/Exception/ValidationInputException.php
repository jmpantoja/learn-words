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

namespace PlanB\Edge\Infrastructure\Symfony\Normalizer\Exception;


use Symfony\Component\Serializer\Exception\MappingException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ValidationInputException extends MappingException
{
    public function __construct(ConstraintViolationListInterface $constraintViolationList)
    {
        $errors = [];
        /** @var ConstraintViolation $violation */
        foreach ($constraintViolationList as $violation) {
            $key = $violation->getPropertyPath();
            $errors[] = sprintf('%s: %s', $key, $violation->getMessage());
        }

        $message = implode("\n", $errors);
        parent::__construct($message);
    }

}
