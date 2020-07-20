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

namespace PlanB\Edge\Infrastructure\Symfony\Form\Exception;


use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class SingleMappingFailedException extends TransformationFailedException
{
    public function __construct(ConstraintViolationListInterface $violations)
    {
        $violation = $violations->get(0);
        $message = $violation->getMessage();
        $code = 0;
        $previous = null;
        $invalidMessage = $this->cleanMessageTemplate($violation);
        $invalidMessageParameters = $violation->getParameters();

        parent::__construct($message, $code, $previous, $invalidMessage, $invalidMessageParameters);
    }

    /**
     * @param ConstraintViolationInterface $violation
     * @return string
     */
    private function cleanMessageTemplate(ConstraintViolationInterface $violation): string
    {
        $template = $violation->getMessageTemplate();
        $pieces = explode('|', $template);

        return array_shift($pieces);
    }

}
