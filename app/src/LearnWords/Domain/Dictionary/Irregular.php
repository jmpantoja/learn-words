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


class Irregular extends Question
{
    private string $past;
    private string $pastParticiple;

    public function __construct(Entry $entry, string $past, string $pastParticiple)
    {
        $this->past = $past;
        $this->pastParticiple = $pastParticiple;

        parent::__construct($entry);
    }

    /**
     * @return string
     */
    public function getPast(): string
    {
        return $this->past;
    }

    /**
     * @return string
     */
    public function getPastParticiple(): string
    {
        return $this->pastParticiple;
    }
    
}
