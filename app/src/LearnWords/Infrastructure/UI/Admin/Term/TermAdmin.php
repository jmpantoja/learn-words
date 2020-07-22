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

namespace LearnWords\Infrastructure\UI\Admin\Term;


use PlanB\Edge\Infrastructure\Sonata\Admin\Admin;

final class TermAdmin extends Admin
{
    public function toString($object)
    {
        return $object->getWord();
    }
}
