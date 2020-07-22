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

use PlanB\Edge\Domain\Validator\ConstraintsFactory;
use PlanB\Edge\Domain\Validator\Traits\ValidableTrait;
use PlanB\Edge\Domain\Validator\Validable;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

final class Clue implements Validable
{
    use ValidableTrait;


    private string $clue;

    public function __construct(string $clue)
    {
        $this->ensureIsValid($clue);
        $this->setClue($clue);
    }

    public static function configureValidator(ConstraintsFactory $factory): void
    {
        $factory->single()
            ->add(new Length([
                'min' => 6
            ]))
            ->add(new Regex([
                'pattern' => '/^[\p{Latin}\(\)\.\:\,\h]*$/u',
                'message' => 'sólo se admiten letras, signos de puntuación o espacios'
            ]));
    }

    private function setClue(string $clue): self
    {
        $this->clue = mb_strtolower($clue);
        return $this;
    }

    /**
     * @return string
     */
    public function getClue(): string
    {
        return $this->clue;
    }


}
