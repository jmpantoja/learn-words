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

namespace LearnWords\Application\User\UseCase;


use LearnWords\Domain\User\AnswerRepository;
use LearnWords\Domain\User\LeitnerStatus;
use LearnWords\Domain\User\Stat;
use LearnWords\Domain\User\StatRepository;
use LearnWords\Domain\User\UserRepository;
use PlanB\Edge\Application\UseCase\UseCaseInterface;

final class UpdateStatsUseCase implements UseCaseInterface
{
    private AnswerRepository $answerRepository;

    private StatRepository $statRepository;

    private UserRepository $userRepository;

    public function __construct(AnswerRepository $answerRepository, StatRepository $statRepository, UserRepository $userRepository)
    {
        $this->answerRepository = $answerRepository;
        $this->statRepository = $statRepository;
        $this->userRepository = $userRepository;
    }

    public function handle(UpdateStats $command)
    {
        $grossList = $this->answerRepository->currentStat();
        $list = [];

        foreach ($grossList as $stat) {
            $userId = $stat['user'];
            $leitner = $stat['leitner'];
            $total = $stat['total'];

            $list[$userId] = $list[$userId] ?? LeitnerStatus::make();
            $list[$userId]->addLeitnerTotal($leitner, $total * 1);
        }

        foreach ($list as $userId => $leitnerStatus) {
            $user = $this->userRepository->find($userId);
            $this->statRepository->createIfNotExists($user, $leitnerStatus);
        }
    }

}
