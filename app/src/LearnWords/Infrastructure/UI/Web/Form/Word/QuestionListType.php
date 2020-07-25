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

namespace LearnWords\Infrastructure\UI\Web\Form\Word;


use PlanB\Edge\Domain\Collection\EntityList;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\SingleType;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class QuestionListType extends SingleType
{
    public function getParent(): string
    {
        return CollectionType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->customOptions($resolver);
    }


    public function customOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'by_reference' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_type' => QuestionType::class
        ]);
    }

    /**
     * @inheritDoc
     */
    public function normalize($value)
    {
        if ($value instanceof EntityList) {
            return $value->toArray();
        }

        return parent::normalize($value);
    }

    public function getClass(): string
    {
        return 'x';
    }

}
