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

namespace LearnWords\Infrastructure\UI\Web\Form;


use LearnWords\Domain\Dictionary\Example;
use LearnWords\Domain\Dictionary\Wording;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\CompositeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ExampleType extends CompositeType
{
    public function customForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('sample', TextType::class);
        $builder->add('translation', TextType::class);
    }

    public function getConstraints()
    {
        return Example::getConstraints();
    }

    public function reverse($data): Example
    {
        return new Example($data['sample'], $data['translation']);
    }
}
