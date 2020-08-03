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

use PlanB\Edge\Infrastructure\Symfony\Form\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class QuestionListType extends CollectionType
{

    public function customOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entry_type' => QuestionType::class
        ]);
    }

//    /**
//     * @inheritDoc
//     */
//    public function reverseTransform($value)
//    {
//        dump($value);
//        die('xx');
//        return $value;
//    }


}
