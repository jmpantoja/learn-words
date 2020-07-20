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

namespace LearnWords\Domain\Term;

use Doctrine\Common\Collections\Collection;
use LearnWords\Domain\Tag\TagList;
use LearnWords\Domain\Term\SaveTerm\TermHasBeenCreated;
use LearnWords\Domain\Term\SaveTerm\TermHasBeenUpdated;
use PlanB\Edge\Domain\Entity\EntityInterface;
use PlanB\Edge\Domain\Entity\Traits\NotifyEvents;

class Term implements EntityInterface
{
    use NotifyEvents;

    private TermId $id;
    private Word $word;
    private Collection $tags;

    private Collection $lines;

    public function __construct(TermId $termId, Word $word, TagList $tagList)
    {
        $this->id = $termId;
        $this->tags = $tagList;

        $this->update($word, $tagList);
        $this->notify(new TermHasBeenCreated($this));
    }

    public function update(Word $word, TagList $tagList): self
    {
        $this->word = $word;
        $this->tags = $tagList->getCollection();

        $this->notify(new TermHasBeenUpdated($this));
        return $this;
    }

    /**
     * @return TermId
     */
    public function getId(): TermId
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


    public function getTags(): Collection
    {
        return $this->tags;
    }


}
