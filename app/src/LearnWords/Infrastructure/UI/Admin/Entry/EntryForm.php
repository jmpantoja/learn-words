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

namespace LearnWords\Infrastructure\UI\Admin\Entry;


use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Infrastructure\Domain\Dictionary\Dto\EntryDto;
use LearnWords\Infrastructure\UI\Web\Form\QuestionListType;
use LearnWords\Infrastructure\UI\Web\Form\TagListType;
use LearnWords\Infrastructure\UI\Web\Form\WordType;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfigurator;
use PlanB\Edge\Infrastructure\Symfony\Form\FormInterface;


final class EntryForm extends FormConfigurator
{
    static public function attachTo(): string
    {
        return EntryAdmin::class;
    }


    public function configure(Entry $word = null): void
    {
        $this->add('word', WordType::class);

        $this->add('questions', QuestionListType::class);

        $this->add('tags', TagListType::class, [
            'property' => 'tag'
        ]);
    }

    public function transform(?object $data)
    {
        if (null === $data) {
            return null;
        }
        return EntryDto::fromObject($data);
    }


}
