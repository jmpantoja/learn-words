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

namespace PlanB\Edge\Infrastructure\Symfony\Form;


use LogicException;
use PlanB\Edge\Infrastructure\Symfony\Form\Exception\SingleMappingFailedException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class SingleDataMapper implements SingleDataMapperInterface
{
    private FormSerializerInterface $serializer;

    private SingleFormTypeInterface $formType;

    public function __construct(FormSerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }


    public function attach(SingleFormTypeInterface $formType): self
    {
        $this->formType = $formType;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        return $this->formType->normalize($this->serializer, $value);
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        $this->validate($value);
        return $this->formType->denormalize($this->serializer, $value);
    }

    /**
     * @param mixed $data
     * @return bool
     */
    private function validate($data): bool
    {
        $violations = $this->formType->validate($data);

        if (0 === $violations->count()) {
            return true;
        }

        throw new SingleMappingFailedException($violations);
    }

}
