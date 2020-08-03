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

namespace LearnWords\Infrastructure\Domain\Word\Dto;

use Doctrine\Common\Collections\Collection;
use LearnWords\Domain\Word\Lang;
use LearnWords\Domain\Word\Question;
use LearnWords\Domain\Word\TagList;
use LearnWords\Domain\Word\Word;
use PlanB\Edge\Domain\Entity\Dto;

final class WordDto extends Dto
{
    public ?string $word = null;
    public ?Lang $lang = null;
    public ?Collection $questions = null;
    public ?Collection $tags = null;


    /**
     * @param Word $word
     * @return Word
     */
    public function update($word): Word
    {
        $tagList = TagList::collect($this->tags);
        $word->update($this->word, $this->lang, $tagList);

        $this->applyQuestions($word);
        return $word;
    }

    private function applyQuestions(Word $word)
    {
        $questionList = $this->questions;

        $questionList->eachUpdate(function (QuestionDto $question, int $key) use ($word) {
            return $word->updateQuestion($key, $question->wording);
        });

        $questionList->eachDelete(function (Question $question) use ($word) {
            return $word->removeQuestion($question);
        });

        $questionList->eachInsert(function (QuestionDto $question) use ($word) {
            return $word->addQuestion($question->wording);
        });
    }

    public function create(): Word
    {
        $tagList = TagList::collect($this->tags);
        $word = new Word($this->word, $this->lang, $tagList);

        $this->applyQuestions($word);
        return $word;
    }
}
