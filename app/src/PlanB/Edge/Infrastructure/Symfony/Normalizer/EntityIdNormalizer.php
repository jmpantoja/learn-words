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


use PlanB\Edge\Domain\Entity\EntityId;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

final class EntityIdNormalizer implements ContextAwareNormalizerInterface
{

    /**
     * @param mixed $data
     * @param string|null $format
     * @param mixed[] $context
     * @return bool
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof EntityId;
    }

    /**
     * @param mixed $object
     * @param string|null $format
     * @param mixed[] $context
     * @return string
     */
    public function normalize($object, $format = null, array $context = []): string
    {
        return (string)$object;
    }
}
