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
use LearnWords\Domain\User\GivenText;
use LearnWords\Domain\User\UserId;

final class UpdateAnswer
{
    private UserId $userId;
    private QuestionId $questionId;
    private GivenText $response;
    private bool $dryRun;


    public function __construct(UserId $userId, QuestionId $questionId, ?string $response, bool $dryRun)
    {
        $this->userId = $userId;
        $this->questionId = $questionId;
        $this->response = new GivenText($response);
        $this->dryRun = $dryRun;
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
     * @return GivenText
     */
    public function getResponse(): GivenText
    {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function isDryRun(): bool
    {
        return $this->dryRun;
    }


}
