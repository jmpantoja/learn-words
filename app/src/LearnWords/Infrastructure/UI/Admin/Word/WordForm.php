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

namespace LearnWords\Infrastructure\UI\Admin\Word;


use LearnWords\Domain\Word\Word;
use LearnWords\Infrastructure\UI\Web\Form\Word\LangType;
use LearnWords\Infrastructure\UI\Web\Form\Word\QuestionListType;
use LearnWords\Infrastructure\UI\Web\Form\Word\TagListType;
use PlanB\Edge\Domain\Collection\SnapshotList;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfigurator;
use Symfony\Component\Form\Extension\Core\Type\TextType;


final class WordForm extends FormConfigurator
{
    public function attachTo(): string
    {
        return WordAdmin::class;
    }

    public function configure(Word $word = null): void
    {
        $this
            ->add('word', TextType::class)
            ->add('lang', LangType::class)
            ->add('questions', QuestionListType::class)
            ->add('tags', TagListType::class);
    }
}
