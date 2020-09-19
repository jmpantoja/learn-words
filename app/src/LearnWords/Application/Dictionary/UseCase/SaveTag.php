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

namespace LearnWords\Application\Dictionary\UseCase;

use LearnWords\Domain\Dictionary\Tag;

final class SaveTag
{
    private Tag $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function getTag(): Tag
    {
        return $this->tag;
    }
}
