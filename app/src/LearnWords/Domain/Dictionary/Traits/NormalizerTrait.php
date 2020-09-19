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

namespace LearnWords\Domain\Dictionary\Traits;


trait NormalizerTrait
{
    public function normalize(string $text): string
    {
        $text = strip_tags_content($text);
        return preg_replace("/^\((.*)\)$/", '\\1', $text);
    }
}
