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

final class ExamCriteria
{
    private User $user;

    private ExamType $type;

    private ?Limit $limit;

    private $tags = [];

    public function __construct(User $user, ExamType $type, ?Limit $limit, string ...$tags)
    {
        $this->user = $user;
        $this->type = $type;
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
     * @return ExamType
     */
    public function getType(): ExamType
    {
        return $this->type;
    }

    /**
     * @return Limit
     */
    public function getLimit(): ?Limit
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

    public function isToday(): bool
    {
        return $this->type->is(ExamType::TODAY());
    }

    public function isDaily(): bool
    {
        return $this->type->is(ExamType::DAILY());
    }

    public function isMostFailed(): bool
    {
        return $this->type->is(ExamType::FAILED());
    }
}
