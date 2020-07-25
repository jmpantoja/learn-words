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

namespace LearnWords\Infrastructure\UI\Web\Form\Word;


use PlanB\Edge\Infrastructure\Symfony\Form\Type\CompositeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class QuestionType extends CompositeType
{

    public function validate(array $data): ConstraintViolationListInterface
    {
        return new ConstraintViolationList();
    }

    public function customForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('wording')
            ->add('description')
            ->add('example_A')
            ->add('example_B');
    }

    function customOptions(OptionsResolver $resolver): void
    {

    }

    public function getClass(): string
    {
        return '';
    }
}
