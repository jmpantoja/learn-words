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

namespace PlanB\Edge\Infrastructure\Symfony\Form\Type;


use PlanB\Edge\Domain\Enum\Enum;
use PlanB\Edge\Infrastructure\Symfony\Validator\SingleBuilder;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;

abstract class EnumType extends SingleType
{

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired('enum_class');
        $resolver->setAllowedTypes('enum_class', ['string']);
        $resolver->setAllowedValues('enum_class', function (string $enumClass) {
            return is_a($enumClass, Enum::class, true);
        });

        $resolver->setNormalizer('choices', function (OptionsResolver $resolver) {
            $enumClass = $resolver['enum_class'];
            $choices = forward_static_call([$enumClass, 'toArray']);
            return array_flip($choices);
        });
    }


    public function buildConstraints(SingleBuilder $builder, array $options): void
    {
        $choices = $options['choices'] ?? [];

        $builder
            ->add(new Choice([
                'choices' => array_values($choices)
            ]));
    }

}
