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

namespace LearnWords\Domain\Term;


use PlanB\Edge\Domain\Validator\Constraints\DataType;
use PlanB\Edge\Domain\Validator\ConstraintsFactory;
use PlanB\Edge\Domain\Validator\Traits\ValidableTrait;
use PlanB\Edge\Domain\Validator\Validable;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

final class Word implements Validable
{
    use ValidableTrait;

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
        $this->ensureIsValid([
            'word' => $word,
            'lang' => $lang
        ]);

        $this->setWord($word);
        $this->lang = $lang;
    }

    public static function configureValidator(ConstraintsFactory $factory): void
    {
        $factory->composite()
            ->required('word', [
                new NotBlank(),
                new Length([
                    'min' => 3
                ]),
                new Regex([
                    'pattern' => '/^[\p{Latin}\h]*$/u',
                    'message' => 'sólo se admiten letras o espacios'
                ])
            ])
            ->required('lang', [
                new DataType([
                    'type' => Lang::class
                ])
            ]);
    }

    private function setWord(string $word): self
    {
        $this->word = mb_strtolower($word);
        return $this;
    }

    /**
     * @return Lang
     */
    public function getLang(): Lang
    {
        return $this->lang;
    }

    /**
     * @return string
     */
    public function getWord(): string
    {
        return $this->word;
    }


    public function __toString()
    {
        return $this->getWord();
    }
}
