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

namespace LearnWords\Infrastructure\UI\Web\Form;


use LearnWords\Domain\Dictionary\TagList;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TagListType extends ModelType
{

    public function customOptions(OptionsResolver $resolver): void
    {

    }

    public function reverse($value): TagList
    {
        return TagList::collect($value);
    }
}
