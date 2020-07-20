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
     * @param mixed $data
     * @param string $type
     * @param null $format
     * @param mixed[] $context
     *
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        return is_a($type, Enum::class, true);
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param null $format
     * @param mixed[] $context
     * @return Enum
     */
    public function denormalize($data, $type, $format = null, array $context = []): Enum
    {
        return forward_static_call([$type, 'make'], $data);
    }
}
