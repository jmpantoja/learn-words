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

namespace LearnWords\Term\Domain\Model;


final class Word
{
    private Lang $lang;
    private string $word;

    public static function english(string $word): self
    {
        return new self($word, Lang::ENGLISH());
    }

    public static function spanish(string $word): self
    {
        return new self($word, Lang::SPANISH());
    }

    public function __construct(string $word, Lang $lang)
    {
        $this->word = $word;
        $this->lang = $lang;
    }

    /**
     * @return Lang
     */
    public function lang(): Lang
    {
        return $this->lang;
    }

    /**
     * @return string
     */
    public function word(): string
    {
        return $this->word;
    }


    public function __toString()
    {
        return $this->word();
    }
}
