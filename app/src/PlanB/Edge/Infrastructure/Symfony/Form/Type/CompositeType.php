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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Throwable;

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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->customOptions($resolver);

        $resolver->setDefaults([
            'compound' => true,
            'by_reference' => false
        ]);
    }

    abstract public function customForm(FormBuilderInterface $builder, array $options): void;

    abstract function customOptions(OptionsResolver $resolver): void;

    /**
     * @param DenormalizerInterface $serializer
     * @param mixed[] $data
     * @param mixed[] $context
     * @return object|null
     */
    public function denormalize(DenormalizerInterface $serializer, array $data, array $context): ?object
    {
        try {
            return $serializer->denormalize($data, $this->getClass(), null, $context);
        } catch (Throwable $e) {
            return $context[ObjectNormalizer::OBJECT_TO_POPULATE];
        }
    }

    abstract public function getClass(): string;
}
