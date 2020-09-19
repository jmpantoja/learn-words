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


use LearnWords\Domain\Dictionary\Wording;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\CompositeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class WordingType extends CompositeType
{
    public function customForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('wording', TextType::class);
        $builder->add('description', TextType::class);
    }

    public function getConstraints()
    {
        return Wording::getConstraints();
    }

    public function reverse($data): Wording
    {
        return new Wording($data['wording'], $data['description']);
    }
}
