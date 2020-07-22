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

namespace LearnWords\Infrastructure\UI\Web\Form\Term;


use Doctrine\ORM\Mapping\ClassMetadata;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\ModelType;
use Sonata\DoctrineORMAdminBundle\Admin\FieldDescription;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TagListType extends ModelType
{

    public function customOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults([
            'property' => 'tag',
        ]);



    }


}
