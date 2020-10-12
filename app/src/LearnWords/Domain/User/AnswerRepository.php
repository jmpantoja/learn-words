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

namespace LearnWords\Domain\User;


use LearnWords\Domain\DailyWork\QuestionCriteria;
use LearnWords\Domain\Dictionary\Question;
use LearnWords\Domain\Dictionary\VocabularyList;

interface AnswerRepository
{
    public function persist(Answer $answer): void;

    public function createIfNotExists(User $user, Question $question): Answer;

    public function currentStat();
}
