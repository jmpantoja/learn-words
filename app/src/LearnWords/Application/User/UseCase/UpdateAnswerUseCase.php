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

namespace LearnWords\Application\User\UseCase;


use LearnWords\Domain\Dictionary\Question;
use LearnWords\Domain\Dictionary\VocabularyRepository;
use LearnWords\Domain\User\AnswerRepository;
use LearnWords\Domain\User\User;
use LearnWords\Domain\User\UserRepository;
use PlanB\Edge\Application\UseCase\UseCaseInterface;

final class UpdateAnswerUseCase implements UseCaseInterface
{
    private UserRepository $userRepository;
    private VocabularyRepository $questionRepository;
    /**
     * @var AnswerRepository
     */
    private AnswerRepository $answerRepository;

    public function __construct(UserRepository $userRepository,
                                VocabularyRepository $questionRepository,
                                AnswerRepository $answerRepository)
    {
        $this->userRepository = $userRepository;
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
    }

    public function handle(UpdateAnswer $command): bool
    {
        $user = $this->findUser($command);
        $question = $this->findQuestion($command);

        if (is_null($user) || is_null($question)) {
            return false;
        }
        $response = $command->getResponse();

        $answer = $this->answerRepository->createIfNotExists($user, $question);

        if ($command->isDryRun()) {
            $answer->dryRun($response);
            $this->answerRepository->persist($answer);
            return true;
        }

        $answer->resolve($response);
        $this->answerRepository->persist($answer);
        return true;

    }

    protected function findUser(UpdateAnswer $command): ?User
    {
        $userId = $command->getUserId();
        return $this->userRepository->find($userId);
    }

    private function findQuestion(UpdateAnswer $command): ?Question
    {
        $questionId = $command->getQuestionId();
        return $this->questionRepository->find($questionId);
    }
}
