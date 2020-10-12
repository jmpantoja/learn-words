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

namespace LearnWords\Infrastructure\UI\GraphQl\Resolver;


use LearnWords\Domain\User\Stat;
use LearnWords\Domain\User\StatRepository;
use LearnWords\Domain\User\UserRepository;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

final class StatsQuery implements ResolverInterface, AliasedInterface
{

    private UserRepository $userRepository;
    /**
     * @var StatRepository
     */
    private StatRepository $statRepository;

    public function __construct(UserRepository $userRepository, StatRepository $statRepository)
    {
        $this->userRepository = $userRepository;
        $this->statRepository = $statRepository;
    }

    public function byUser(string $userId): array
    {
        $user = $this->userRepository->findOneById($userId);

        $statList = $this->statRepository->byUser($user);

        return $statList
            ->sortByDate()
            ->map(function (Stat $stat) {
            return $stat->toArray();
        })->toArray();
    }

    /**
     * @inheritDoc
     */
    public static function getAliases(): array
    {
        return [
            'byUser' => 'stats_by_user',
        ];
    }


}
