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

namespace LearnWords\Infrastructure\UI\Command;

use League\Tactician\CommandBus;
use LearnWords\Application\User\UseCase\UpdateStats;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateStatsCommand extends Command
{
    protected static $defaultName = 'update:stats';

    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        parent::__construct(null);

        $this->commandBus = $commandBus;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->commandBus->handle(new UpdateStats());
        return 0;
    }
}
