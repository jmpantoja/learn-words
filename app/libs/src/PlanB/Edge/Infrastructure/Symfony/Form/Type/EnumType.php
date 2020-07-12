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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\ValidatorBuilder;

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

    public function validate($data): ConstraintViolationListInterface
    {
        $validator = (new ValidatorBuilder())->getValidator();

        $choices = $options['choices'] ?? [];
        $constraints = [
            new Choice([
                'choices' => array_values($choices)
            ])
        ];
        return $validator->validate($data, $constraints);
    }
}
