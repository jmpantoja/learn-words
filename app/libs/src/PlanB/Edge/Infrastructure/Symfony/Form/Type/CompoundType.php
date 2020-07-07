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


use PlanB\Edge\Infrastructure\Symfony\Form\CompoundDataMapper;
use PlanB\Edge\Infrastructure\Symfony\Form\CompoundToObjectMapperInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class CompoundType extends AbstractType implements CompoundToObjectMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setDataMapper(new CompoundDataMapper($this, $options));

        $this->customForm($builder, $options);

        foreach ($builder as $name => $form) {
            $form->setPropertyPath($name);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $this->customOptions($resolver);

        $resolver->setDefaults([
            'compound' => true,
            'by_reference' => false
        ]);
    }

    abstract public function customForm(FormBuilderInterface $builder, array $options);

    abstract function customOptions(OptionsResolver $resolver);

}
