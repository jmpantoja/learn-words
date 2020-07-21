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

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'enum_class' => $this->getClass(),
            'choices' => []
        ]);

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

    public function customOptions(OptionsResolver $resolver): void
    {

    }

    public function validate($data): ConstraintViolationListInterface
    {
        $validator = (new ValidatorBuilder())->getValidator();

        $enumClass = $this->getClass();
        $choices = forward_static_call([$enumClass, 'toArray']);

        $constraints = [
            new Choice([
                'choices' => array_keys($choices)
            ])
        ];

        return $validator->validate($data, $constraints);
    }
}
