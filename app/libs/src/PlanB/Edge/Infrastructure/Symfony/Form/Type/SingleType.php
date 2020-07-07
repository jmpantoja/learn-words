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


use PlanB\Edge\Infrastructure\Symfony\Form\ArrayDataMapper2;
use PlanB\Edge\Infrastructure\Symfony\Form\SingleDataMapper;
use PlanB\Edge\Infrastructure\Symfony\Form\SingleToObjectMapperInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class SingleType extends AbstractType implements SingleToObjectMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new SingleDataMapper($this, $options));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $this->customOptions($resolver);

        $resolver->setDefaults([
            'compound' => false,
            'by_reference' => false
        ]);
    }

    public function getParent()
    {
        return TextType::class;
    }

    abstract public function customOptions(OptionsResolver $resolver);

}
