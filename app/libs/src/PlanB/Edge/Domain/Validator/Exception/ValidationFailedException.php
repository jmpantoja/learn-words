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

namespace PlanB\Edge\Domain\Validator\Exception;


use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ValidationFailedException extends \Exception
{
    private ConstraintViolationListInterface $violationList;

    private array $errors = [];

    public function __construct(ConstraintViolationListInterface $violationList)
    {

        $this->violationList = $violationList;
        $pieces = [];

        foreach ($violationList as $violation) {
            $path = $violation->getPropertyPath();
            $message = $violation->getMessage();
            $this->errors[$path] = $message;

            $pieces[] = sprintf('%s => %s', $path, $message);
        }

        parent::__construct(implode("\n", $pieces));
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function violationList(): ConstraintViolationListInterface
    {
        return $this->violationList;
    }

    /**
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
