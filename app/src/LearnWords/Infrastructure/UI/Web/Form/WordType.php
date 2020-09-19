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

use LearnWords\Domain\Dictionary\Word;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\CompositeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class WordType extends CompositeType
{

    public function customForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('word', TextType::class);
        $builder->add('lang', LangType::class);
    }


    public function getConstraints()
    {
        return Word::getConstraints();
    }

    public function reverse($data): Word
    {
        return new Word($data['word'], $data['lang']);
    }
}
