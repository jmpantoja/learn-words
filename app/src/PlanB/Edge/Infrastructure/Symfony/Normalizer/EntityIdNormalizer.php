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
use PlanB\Edge\Domain\Enum\Enum;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class EntityIdNormalizer implements NormalizerInterface
{
    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof EntityId;
    }

    /**
     * @inheritDoc
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return (string)$object;
    }

}
