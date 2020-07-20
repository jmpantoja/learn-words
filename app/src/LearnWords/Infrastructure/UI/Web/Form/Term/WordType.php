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

namespace LearnWords\Infrastructure\UI\Web\Form\Term;


use LearnWords\Domain\Term\Word;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\CompositeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class WordType extends CompositeType
{
    public function customForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('word', TextType::class)
            ->add('lang', LangType::class);
    }

    public function customOptions(OptionsResolver $resolver): void
    {
    }

    public function validate(array $data): ConstraintViolationListInterface
    {
        return Word::validate($data);
    }

    public function getClass(): string
    {
        return Word::class;
    }
}
