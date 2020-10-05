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

//    public function __construct(Entry $entry, Wording $wording)
//    {
//        parent::__construct($entry, $wording);
//    }


}
