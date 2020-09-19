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

use Doctrine\ORM\Mapping\ClassMetadata;
use Sonata\AdminBundle\Form\Type\ModelType as SonataModelType;
use Sonata\DoctrineORMAdminBundle\Admin\FieldDescription;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class ModelType extends SonataModelType implements DataTransformerInterface
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setByReference(false);
        parent::buildForm($builder, $options);

        $builder->addModelTransformer($this);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setNormalizer('multiple', function (OptionsResolver $resolver) {
            return $this->isToMany($resolver['sonata_field_description']);
        });

        $this->customOptions($resolver);
    }

    private function isToMany(FieldDescription $fieldDescription): bool
    {
        $mappingType = $fieldDescription->getMappingType();
        return in_array($mappingType, [ClassMetadata::MANY_TO_MANY, ClassMetadata::ONE_TO_MANY]);
    }

    abstract public function customOptions(OptionsResolver $resolver): void;

    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        return $this->reverse($value);
    }

    /**
     * @param mixed $value
     * @return object
     */
    abstract public function reverse($value): object;
}
