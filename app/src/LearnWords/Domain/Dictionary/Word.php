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

namespace LearnWords\Domain\Dictionary;


use PlanB\Edge\Domain\Validator\ValidableTrait;

final class Word
{
    use ValidableTrait;

    private string $word;
    private Lang $lang;

    public static function Spanish(string $word): self
    {
        return new self($word, Lang::SPANISH());
    }

    public static function English(string $word): self
    {
        return new self($word, Lang::ENGLISH());
    }

    public function __construct(string $word, Lang $lang)
    {
        $this->ensure(get_defined_vars());
        $this->word = $word;
        $this->lang = $lang;
    }

    public static function getConstraints()
    {
        return new Constraints\Word();
    }

    /**
     * @return string
     */
    public function getWord(): string
    {
        return $this->word;
    }

    /**
     * @return Lang
     */
    public function getLang(): Lang
    {
        return $this->lang;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->word;
    }

    public function isEnglish()
    {
        return $this->lang->is(Lang::ENGLISH());
    }

    public function isSpanish()
    {
        return $this->lang->is(Lang::SPANISH());
    }

}
