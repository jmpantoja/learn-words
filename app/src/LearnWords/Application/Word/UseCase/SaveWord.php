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
use PlanB\Edge\Application\UseCase\SaveCommand;

final class SaveWord
{
    private Word $word;

    public function __construct(Word $word)
    {
        $this->word = $word;
    }

    public function getWord(): Word
    {
        return $this->word;
    }


}
