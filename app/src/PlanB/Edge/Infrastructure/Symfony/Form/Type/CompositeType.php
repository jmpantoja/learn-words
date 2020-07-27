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


use PlanB\Edge\Infrastructure\Symfony\Form\CompositeDataMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\CompositeFormTypeInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class CompositeType extends AbstractType implements CompositeFormTypeInterface
{
    private CompositeDataMapperInterface $dataMapper;

    public function setDataMapper(CompositeDataMapperInterface $dataMapper): self
    {
        $this->dataMapper = $dataMapper->attach($this);

        return $this;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setDataMapper($this->dataMapper);
        $this->customForm($builder, $options);
        $builder->setByReference(false);
        $builder->setCompound(true);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->customOptions($resolver);
    }

    abstract public function customForm(FormBuilderInterface $builder, array $options): void;

    abstract function customOptions(OptionsResolver $resolver): void;

    /**
     * @inheritDoc
     */
    public function normalize(FormSerializerInterface $serializer, $data)
    {
        return $serializer->normalize($data);
    }

}
