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

namespace LearnWords\Domain\Word;

use Doctrine\Common\Collections\Collection;
use LearnWords\Domain\Word\WordPersistence\WordHasBeenCreated;
use LearnWords\Domain\Word\WordPersistence\WordHasBeenUpdated;
use PlanB\Edge\Domain\Entity\EntityInterface;
use PlanB\Edge\Domain\Entity\Traits\NotifyEvents;


class Word implements EntityInterface
{
    use NotifyEvents;

    private WordId $id;
    private string $word;
    private Lang $lang;
    private Collection $questions;
    private Collection $tags;

    public function __construct(string $word, Lang $lang, TagList $tagList = null)
    {
        $this->id = new WordId();
        $this->questions = QuestionList::empty();

        $this->initialize($word, $lang, $tagList);

        $this->notify(new WordHasBeenCreated($this));
    }

    protected function initialize(string $word, Lang $lang, ?TagList $tagList): self
    {
        $this->word = $word;
        $this->lang = $lang;
        $this->tags = $tagList ?? TagList::empty();

        return $this;
    }

    public function update(string $word, Lang $lang, TagList $tagList = null): self
    {
        $this->initialize($word, $lang, $tagList);
        $this->notify(new WordHasBeenUpdated($this));

        return $this;
    }

    public function getId(): WordId
    {
        return $this->id;
    }

    public function getWord(): string
    {
        return $this->word;
    }

    /**
     * @return Lang
     */
    public function getLang(): Lang
    {
        return $this->lang;
    }

    public function getTags(): TagList
    {
        return TagList::collect($this->tags);
    }

    public function addQuestion(string $wording, string $description): self
    {
        $questionList = $this->getQuestions();


        $question = new Question($this, $wording);
        $questionList->add($question);
        return $this;
    }

    public function getQuestions(): QuestionList
    {
        return QuestionList::collect($this->questions);
    }

}
