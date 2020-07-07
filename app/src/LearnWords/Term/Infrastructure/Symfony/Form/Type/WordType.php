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

namespace LearnWords\Term\Infrastructure\Symfony\Form\Type;


use LearnWords\Term\Domain\Model\Lang;
use LearnWords\Term\Domain\Model\Word;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\CompoundType;
use PlanB\Edge\Infrastructure\Symfony\Validator\CompoundBuilder;
use PlanB\Edge\Infrastructure\Symfony\Validator\Constraints\DataType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class WordType extends CompoundType
{
    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('word', TextType::class)
            ->add('lang', LangType::class);
    }

    public function customOptions(OptionsResolver $resolver)
    {
    }

    public function buildConstraints(CompoundBuilder $builder, array $options): void
    {
        $builder
            ->required('word', [
                new NotBlank(),
                new Length([
                    'min' => 3
                ])
            ])
            ->required('lang', [
                new DataType([
                    'type' => Lang::class
                ])
            ]);
    }

    public function mapDataToObject(array $data): object
    {
        $word = (string)$data['word'];
        $lang = $data['lang'];

        return new Word($word, $lang);
    }
}
