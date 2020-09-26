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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use LearnWords\Domain\Dictionary\EntryPersistence\EntryHasBeenCreated;
use LearnWords\Domain\Dictionary\EntryPersistence\EntryHasBeenUpdated;
use PlanB\Edge\Domain\Entity\Traits\NotifyEvents;


class Entry
{
    use NotifyEvents;

    private EntryId $id;

    private Word $word;

    private Collection $tags;

    private Collection $questions;

    private ?Mp3Url $mp3Url;

    public function __construct(Word $word, TagList $tagList, array $questions = [], Mp3Url $mp3Url = null)
    {
        $this->id = new EntryId();
        $this->word = $word;
        $this->tags = $tagList;
        $this->questions = new ArrayCollection();
        $this->mp3Url = $mp3Url;

        foreach ($questions as $question) {
            $this->addQuestion($question);
        }

        $this->notify(new EntryHasBeenCreated($this));
    }

    public function update(Word $word, TagList $tagList): self
    {
        $this->word = $word;
        $this->tags = $tagList;

        $this->notify(new EntryHasBeenUpdated($this));
        return $this;
    }


    /**
     * @return EntryId
     */
    public function getId(): EntryId
    {
        return $this->id;
    }

    /**
     * @return Word
     */
    public function getWord(): Word
    {
        return $this->word;
    }

    public function getTags(): TagList
    {
        return TagList::collect($this->tags);
    }

    public function setTags(TagList $tagList): self
    {
        $this->tags = $tagList;
        return $this;
    }

    public function getQuestions(): QuestionList
    {
        return QuestionList::collect($this->questions);
    }

    public function getMp3Url(): ?Mp3Url
    {
        return $this->mp3Url;
    }

    public function addQuestion(array $data): self
    {
        $total = count($this->questions) + 1;
        $relevance = new Relevance($total);

        $question = new Question($this, $data['wording'], $data['example'], $relevance);
        $this->questions->add($question);
        return $this;
    }

    public function updateQuestion($key, array $data): self
    {
        $question = $this->questions->get($key);
        $question->update($data['wording'], $data['example'], $data['relevance']);
        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        $this->questions->removeElement($question);
        return $this;
    }
}
