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

namespace PlanB\Edge\Infrastructure\Symfony\Serializer\Normalizer;


use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

final class DomainEventNormalizer extends PropertyNormalizer
{
    const IGNORED_ATTRIBUTES = ['when'];

    /**
     * @inheritDoc
     */
    protected function extractAttributes($object, $format = null, array $context = [])
    {
        $attributes = parent::extractAttributes($object, $format, $context);

        $attributes = array_filter($attributes, fn(string $value) => !in_array($value, self::IGNORED_ATTRIBUTES));
        return $attributes;
    }

    /**
     * @inheritDoc
     */
    protected function getAttributeValue($object, $attribute, $format = null, array $context = [])
    {
        $value = parent::getAttributeValue($object, $attribute, $format, $context);

        if (is_stringable($value)) {
            return (string)$value;
        }
        return $value;
    }
}


