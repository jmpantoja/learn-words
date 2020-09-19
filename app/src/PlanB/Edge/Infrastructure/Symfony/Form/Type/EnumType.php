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
use PlanB\Edge\Domain\Validator\ConstraintsFactory;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            'enum_class' => $this->getDataClass(),
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

    abstract public function getDataClass(): string;

    public function getConstraints()
    {
        return null;
    }

    public function customOptions(OptionsResolver $resolver): void
    {

    }

    /**
     * @param mixed $data
     * @return Enum|null
     */
    public function reverse($data): ?Enum
    {
        if (!forward_static_call([$this->getDataClass(), 'hasKey'], $data)) {
            return null;
        }

        return forward_static_call([$this->getDataClass(), 'make'], $data);
    }

    /**
     * @param object|null $value
     * @return mixed
     */
    public function transform($value)
    {
        return (string)$value;
    }

}
