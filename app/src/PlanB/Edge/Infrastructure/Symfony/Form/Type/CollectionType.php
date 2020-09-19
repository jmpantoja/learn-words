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

use LearnWords1\Domain\Word\QuestionList;
use PlanB\Edge\Domain\Collection\SnapshotList;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializerInterface;
use Sonata\AdminBundle\Form\Type\CollectionType as SonataCollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class CollectionType extends SingleType
{
    /**
     * @return string
     */
    final public function getParent(): string
    {
        return SonataCollectionType::class;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder->setCompound(true);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->customOptions($resolver);

        $resolver->setDefaults([
            'allow_add' => true,
            'allow_delete' => true,
        ]);
    }

    /**
     * @param object|null $value
     * @return object|SnapshotList|null
     */
    public function transform($value)
    {
        $value = $value ?? [];

        if (is_iterable($value)) {
            return SnapshotList::collect($value);
        }
        return null;
    }

    /**
     * @return array|null
     */
    public function getConstraints()
    {
        return null;
    }

    /**
     * @param mixed $data
     * @return SnapshotList
     */
    public function reverse($data): SnapshotList
    {
        return $data;
    }


}
