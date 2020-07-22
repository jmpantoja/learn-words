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


use PlanB\Edge\Infrastructure\Symfony\Form\SingleDataMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\SingleFormTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class SingleType extends AbstractType implements SingleFormTypeInterface
{
    private SingleDataMapperInterface $dataMapper;

    public function setDataMapper(SingleDataMapperInterface $dataMapper): self
    {
        $this->dataMapper = $dataMapper->attach($this);
        return $this;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this->dataMapper);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->customOptions($resolver);

        $resolver->setDefaults([
            'compound' => false,
            'by_reference' => false
        ]);
    }

    public function getParent(): string
    {
        return TextType::class;
    }

    abstract public function customOptions(OptionsResolver $resolver): void;

    /**
     * @param DenormalizerInterface $serializer
     * @param mixed $data
     * @param mixed[] $context
     * @return object|null
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function denormalize(DenormalizerInterface $serializer, $data, array $context): ?object
    {
        return $serializer->denormalize($data, $this->getClass(), null, $context);
    }


    abstract public function getClass(): string;


    /**
     * @param mixed $data
     * @return ConstraintViolationListInterface
     */
    public function validate($data): ConstraintViolationListInterface
    {
        return new ConstraintViolationList();
    }
}
