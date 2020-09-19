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

namespace PlanB\Edge\Domain\Transformer;


use PlanB\Edge\Domain\Dto\Dto;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

abstract class Transformer implements DenormalizerInterface
{
    /**
     * @param mixed $data
     * @param string $type
     * @param null $format
     * @param array $context
     * @return array|object
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {

        $object = $context[ObjectNormalizer::OBJECT_TO_POPULATE] ?? null;
        if (null === $object) {
            return $this->create($data);
        }

        return $this->update($data, $object);
    }

    /**
     * @param Dto $data
     * @return object
     */
    abstract public function create(Dto $data): object;

    /**
     * @param Dto $data
     * @param object $object
     * @return object
     */
    abstract public function update(Dto $data, $object): object;


}
