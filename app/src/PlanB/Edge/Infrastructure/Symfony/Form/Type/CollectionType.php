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

use PlanB\Edge\Domain\Collection\SnapshotList;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializerInterface;
use Sonata\AdminBundle\Form\Type\CollectionType as SonataCollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class CollectionType extends SingleType
{
    final public function getParent(): string
    {
        return SonataCollectionType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder->setCompound(true);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->customOptions($resolver);

        $resolver->setDefaults([
            'allow_add' => true,
            'allow_delete' => true,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        $value = $value ?? [];
        return $this->toValue($value);
    }

    /**
     * @inheritDoc
     */
    public function toValue($value)
    {
        $value = $value ?? [];

        if (is_iterable($value)) {
            return SnapshotList::collect($value);
        }
        return null;
    }


}
