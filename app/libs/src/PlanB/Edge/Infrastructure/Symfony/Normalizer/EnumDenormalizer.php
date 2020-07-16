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


use PlanB\Edge\Domain\Enum\Enum;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

final class EnumDenormalizer implements ContextAwareDenormalizerInterface
{

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return is_a($type, Enum::class, true);
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        return forward_static_call([$type, 'make'], $data);
    }
}
