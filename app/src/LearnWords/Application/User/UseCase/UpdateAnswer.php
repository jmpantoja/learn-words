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
use LearnWords\Domain\Dictionary\QuestionId;
use LearnWords\Domain\User\AnswerStatus;
use LearnWords\Domain\User\UserId;

final class UpdateAnswer
{
    private UserId $userId;
    private QuestionId $questionId;
    private AnswerStatus $status;

    public function __construct(UserId $userId, QuestionId $questionId, AnswerStatus $status)
    {
        $this->userId = $userId;
        $this->questionId = $questionId;
        $this->status = $status;
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return Question
     */
    public function getQuestionId(): QuestionId
    {
        return $this->questionId;
    }

    /**
     * @return AnswerStatus
     */
    public function getStatus(): AnswerStatus
    {
        return $this->status;
    }

}
