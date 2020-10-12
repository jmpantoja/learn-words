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


interface StatRepository
{
    public function persist(Stat $stat): void;

    public function createIfNotExists(User $user, LeitnerStatus $status);

    public function byUser(User $user): StatList;
}
