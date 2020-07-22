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

namespace LearnWords\Application\Word\UseCase;


use LearnWords\Domain\Word\Word;

final class DeleteWord
{
    private Word $word;

    /**
     * DeleteWord constructor.
     * @param Word $word
     */
    public function __construct(Word $word)
    {
        $this->word = $word;
    }

    /**
     * @return Word
     */
    public function getWord(): Word
    {
        return $this->word;
    }

}
