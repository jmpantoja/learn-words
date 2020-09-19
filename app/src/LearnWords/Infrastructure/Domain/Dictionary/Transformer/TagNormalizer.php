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

namespace LearnWords\Infrastructure\Domain\Dictionary\Transformer;


use LearnWords\Domain\Dictionary\Tag;
use LearnWords\Infrastructure\Domain\Dictionary\Dto\TagDto;
use PlanB\Edge\Domain\Dto\Dto;
use PlanB\Edge\Domain\Transformer\Transformer;

final class TagNormalizer extends Transformer
{
    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === Tag::class;
    }

    /**
     * @param TagDto $data
     * @return Tag
     */
    public function create(Dto $data): Tag
    {
        return new Tag($data->tag);
    }

    /**
     * @param TagDto $data
     * @param Tag $object
     * @return Tag
     */
    public function update(Dto $data, $object): Tag
    {
        return $object->update($data->tag);
    }


}
