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

namespace LearnWords\Domain\Dictionary;

use LearnWords\Domain\User\User;

interface VocabularyRepository
{
    public function getVocabularyByCriteria(VocabularyCriteria $criteria): VocabularyList;

    public function getExamByCriteria(ExamCriteria $criteria): VocabularyList;

    public function getDailyReview(User $user): VocabularyList;

    public function getDailyReviewCount(User $user): int;
}
