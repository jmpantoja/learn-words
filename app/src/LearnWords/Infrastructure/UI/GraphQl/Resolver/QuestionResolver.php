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

namespace LearnWords\Infrastructure\UI\GraphQl\Resolver;


use LearnWords\Domain\Dictionary\Question;
use LearnWords\Domain\Dictionary\QuestionRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

final class QuestionResolver implements ResolverInterface, AliasedInterface
{

    private QuestionRepository $repository;

    public function __construct(QuestionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findQuestionsByTag(Argument $argument)
    {
        $questions = $this->repository->findByTag(...$argument['tags']);

        $output = [];
        /** @var Question $question */
        foreach ($questions as $question) {
            $output[] = [
                'word' => $question->getEntry()->getWord()->getWord(),
                'wording' => $question->getWording()->getWording(),
                'description' => $question->getWording()->getDescription(),
                'sample' => $question->getExample()->getSample(),
                'translation' => $question->getExample()->getTranslation(),
                'sound' => $question->getEntry()->getMp3Url()->getUrl()
            ];
        }

        return $output;

    }

    /**
     * @inheritDoc
     */
    public static function getAliases(): array
    {
        return [
            'findQuestionsByTag' => 'questions_by_tag'
        ];
    }
}
