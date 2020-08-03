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


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class SingleType extends AbstractType implements DataTransformerInterface
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this);
        $builder->setByReference(false);
        $builder->setCompound(false);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->customOptions($resolver);
    }

    abstract public function customOptions(OptionsResolver $resolver): void;

    public function getParent(): string
    {
        return TextType::class;
    }

    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }
        return $this->toValue($value);
    }

    /**
     * @param mixed $data
     * @return mixed
     */
    abstract protected function toValue($data);

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        return $value;
    }

}
