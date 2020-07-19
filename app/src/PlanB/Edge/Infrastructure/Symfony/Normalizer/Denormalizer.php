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
use Symfony\Component\Serializer\Exception\MappingException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class Denormalizer implements ContextAwareDenormalizerInterface, SerializerAwareInterface
{

    private SerializerInterface $serializer;

    /**
     * @inheritDoc
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {

        try {
            $entity = $context[ObjectNormalizer::OBJECT_TO_POPULATE] ?? null;
            return $this->mapToObject($data, $entity);
        } catch (Exception $exception) {
            throw new MappingException($exception->getMessage());
        }
    }

    abstract protected function mapToObject($data): object;

    /**
     * @param $data
     * @param string $type
     * @return mixed
     */
    public function partial($data, string $type)
    {
        return $this->serializer->denormalize($data, $type);
    }


}
