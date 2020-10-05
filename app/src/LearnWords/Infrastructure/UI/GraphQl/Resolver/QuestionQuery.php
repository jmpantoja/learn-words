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


use LearnWords\Domain\Dictionary\Limit;
use LearnWords\Domain\Dictionary\Question;
use LearnWords\Domain\Dictionary\VocabularyCriteria;
use LearnWords\Domain\Dictionary\VocabularyList;
use LearnWords\Domain\Dictionary\VocabularyRepository;
use LearnWords\Domain\Dictionary\Relevance;
use LearnWords\Domain\User\UserRepository;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

final class QuestionQuery implements ResolverInterface, AliasedInterface
{

    private UserRepository $userRepository;

    private VocabularyRepository $questionRepository;

    public function __construct(UserRepository $userRepository, VocabularyRepository $questionRepository)
    {
        $this->userRepository = $userRepository;
        $this->questionRepository = $questionRepository;
    }

    public function findQuestionsByTag(string $userId, int $limit, int $level, array $tags): array
    {

        $user = $this->userRepository->findOneById($userId);

        $relevance = new Relevance($level);
        $limit = new Limit($limit);
        $criteria = new VocabularyCriteria($user, $relevance, $limit, ...$tags);

        $questions = $this->questionRepository->getVocabularyByCriteria($criteria);

        return $this->listToOutput($questions);
    }

    public function getDailyReview(string $userId): array
    {
        $user = $this->userRepository->findOneById($userId);
        $questions = $this->questionRepository->getDailyReview($user);

        return $this->listToOutput($questions);
    }

    public function getDailyReviewCount(string $userId): int
    {
        $user = $this->userRepository->findOneById($userId);
        return $this->questionRepository->getDailyReviewCount($user);

    }

    /**
     * @param VocabularyList $questions
     * @return array
     */
    private function listToOutput(VocabularyList $questions): array
    {
        $output = [];
        /** @var Question $question */
        foreach ($questions as $question) {
            $output[] = [
                'id' => (string)$question->getId(),
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
            'findQuestionsByTag' => 'questions_by_tag',
            'getDailyReview' => 'daily_review',
            'getDailyReviewCount' => 'daily_review_count',
        ];
    }


}
