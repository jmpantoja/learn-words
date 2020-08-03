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

namespace LearnWords\Infrastructure\Domain\Word\Denormalizer;


use LearnWords\Domain\Word\Tag;
use LearnWords\Infrastructure\Domain\Word\Dto\TagDto;
use PlanB\Edge\Infrastructure\Symfony\Normalizer\InputDenormalizer;

final class TagDenormalizer extends InputDenormalizer
{
    /**
     * @inheritDoc
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        $dto = TagDto::fromArray($data);
        return $this->instanceOfFail($dto, $context);
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return is_array($data) && $type === Tag::class;
    }
}
