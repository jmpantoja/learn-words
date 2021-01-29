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

final class VocabularyCriteria
{
    /**
     * @var User
     */
    private User $user;
    /**
     * @var Relevance
     */
    private Relevance $relevance;

    private Limit $limit;
    /**
     * @var string[]
     */
    private array $tags;

    public function __construct(User $user, Relevance $relevance, Limit $limit, string ...$tags)
    {
        $this->user = $user;
        $this->relevance = $relevance;
        $this->limit = $limit;
        $this->tags = array_filter($tags, fn(string $tag) => $tag !== 'all');
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Relevance
     */
    public function getRelevance(): Relevance
    {
        return $this->relevance;
    }

    /**
     * @return int
     */
    public function getLimit(): Limit
    {
        return $this->limit;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }


}
