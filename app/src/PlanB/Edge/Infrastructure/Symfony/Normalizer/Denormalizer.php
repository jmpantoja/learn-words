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


use Exception;
use LogicException;
use Symfony\Component\Serializer\Exception\MappingException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class Denormalizer implements ContextAwareDenormalizerInterface, SerializerAwareInterface
{

    private DenormalizerInterface $serializer;

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

    /**
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @param mixed[] $context
     * @return object
     */
    public function denormalize($data, $type, $format = null, array $context = []): object
    {
        try {
            $entity = $context[ObjectNormalizer::OBJECT_TO_POPULATE] ?? null;
            return $this->mapToObject($data, $entity);
        } catch (Exception $exception) {
            throw new MappingException($exception->getMessage());
        }
    }

    /**
     * @param mixed $data
     * @return object
     */
    abstract protected function mapToObject($data): object;

    /**
     * @param mixed $data
     * @param string $type
     * @return object
     */
    public function partial($data, string $type): object
    {
        return $this->serializer->denormalize($data, $type);
    }


}
