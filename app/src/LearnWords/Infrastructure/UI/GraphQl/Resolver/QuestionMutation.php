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


use League\Tactician\CommandBus;
use LearnWords\Application\User\UseCase\UpdateAnswer;
use LearnWords\Domain\Dictionary\QuestionId;
use LearnWords\Domain\User\AnswerStatus;
use LearnWords\Domain\User\UserId;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

final class QuestionMutation implements MutationInterface, AliasedInterface
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function solveQuestion(string $user, string $question, ?string $response = null, ?bool $dryRun = null)
    {
        $userId = new UserId($user);
        $questionId = new QuestionId($question);

        $command = new UpdateAnswer($userId, $questionId, $response, $dryRun);

        return $this->commandBus->handle($command);
    }

    public static function getAliases(): array
    {
        return [
            'solveQuestion' => 'solve_question'
        ];
    }
}
