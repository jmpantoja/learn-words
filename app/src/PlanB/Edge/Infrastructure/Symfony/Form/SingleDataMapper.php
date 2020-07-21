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
    private DenormalizerInterface $serializer;

    private SingleFormTypeInterface $objectMapper;

    public function __construct(SerializerInterface $serializer)
    {
        $this->setSerializer($serializer);
    }

    /**
     * @param SerializerInterface $serializer
     * @return $this
     */
    public function setSerializer(SerializerInterface $serializer): self
    {
        if (!$serializer instanceof DenormalizerInterface) {
            throw new LogicException('Expected a serializer that also implements DenormalizerInterface.');
        }

        $this->serializer = $serializer;
        return $this;
    }

    public function attach(SingleFormTypeInterface $objectMapper): self
    {
        $this->objectMapper = $objectMapper;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        $this->validate($value);

        return $this->objectMapper->denormalize($this->serializer, $value, [
            ObjectNormalizer::OBJECT_TO_POPULATE => null
        ]);
    }

    /**
     * @param mixed $data
     * @return bool
     */
    private function validate($data): bool
    {
        $violations = $this->objectMapper->validate($data);

        if (0 === $violations->count()) {
            return true;
        }

        throw new SingleMappingFailedException($violations);
    }

}
