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
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class FormSerializer implements FormSerializerInterface
{
    private DenormalizerInterface $denormalizer;
    private NormalizerInterface $normalizer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->initialize($serializer);
    }

    /**
     * @param SerializerInterface $serializer
     */
    protected function initialize(SerializerInterface $serializer): void
    {
        if (!$serializer instanceof DenormalizerInterface) {
            throw new LogicException('Expected a serializer that also implements DenormalizerInterface.');
        }

        if (!$serializer instanceof NormalizerInterface) {
            throw new LogicException('Expected a serializer that also implements NormalizerInterface.');
        }

        $this->denormalizer = $serializer;
        $this->normalizer = $serializer;
    }

    /**
     * @inheritDoc
     */
    public function normalize($data, string $type = null)
    {
        return $this->normalizer->normalize($data, $type);
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, ?object $subject, ?string $type = null)
    {
        if (null === $type) {
            return $data;
        }

        return $this->denormalizer->denormalize($data, $type, null, [
            ObjectNormalizer::OBJECT_TO_POPULATE => $subject
        ]);
    }

}
