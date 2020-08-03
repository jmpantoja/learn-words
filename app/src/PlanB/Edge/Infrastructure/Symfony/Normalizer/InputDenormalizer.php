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

namespace PlanB\Edge\Infrastructure\Symfony\Normalizer;


use PlanB\Edge\Domain\Entity\Dto;
use PlanB\Edge\Domain\Validator\ValidatorAwareInterface;
use PlanB\Edge\Infrastructure\Symfony\Normalizer\Exception\ValidationInputException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class InputDenormalizer implements DenormalizerInterface, ValidatorAwareInterface
{

    private ValidatorInterface $validator;


    public function setValidator(ValidatorInterface $validator): self
    {
        $this->validator = $validator;
        return $this;
    }

    public function instanceOfFail(Dto $dto, array $context): object
    {
        $this->ensure($dto);
        $subject = $context[ObjectNormalizer::OBJECT_TO_POPULATE] ?? null;

        return $dto->process($subject);
    }

    protected function ensure(Dto $dto): Dto
    {
        $violations = $this->validator->validate($dto);
        if ($violations->count() === 0) {
            return $dto;
        }

        throw new ValidationInputException($violations);
    }

}
