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


use PlanB\Edge\Infrastructure\Symfony\Form\Type\CompositeType;
use Symfony\Component\Form\FormBuilderInterface;

final class QuestionType extends CompositeType
{
    public function customForm(FormBuilderInterface $builder, array $options): void
    {
        //   $builder->add('relevance', RelevanceType::class);
        $builder->add('wording', WordingType::class);
        $builder->add('example', ExampleType::class);
    }

    public function reverse($data): array
    {
        return $data;
    }
}
