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
use LearnWords\Application\Dictionary\UseCase\SaveEntry;
use LearnWords\Domain\Dictionary\Importer\ImportEntriesResolver;
use LearnWords\Domain\Dictionary\Importer\ImportResolverInterface;
use LearnWords\Domain\Dictionary\Importer\Reader\ReaderInterface;
use LearnWords\Domain\Dictionary\Provider\QuestionImporter;
use LearnWords\Infrastructure\Domain\Dictionary\Importer\Reader\EntriesCSVReader;
use SplFileInfo;

final class ImportEntriesCommand extends ImportCommand
{
    protected static $defaultName = 'import:entries';

    private ImportResolverInterface $resolver;
    private CommandBus $commandBus;

    public function __construct(ImportEntriesResolver $resolver, CommandBus $commandBus)
    {
        parent::__construct(null);

        $this->resolver = $resolver;
        $this->commandBus = $commandBus;
    }

    protected function getResolver(): ImportResolverInterface
    {
        return $this->resolver;
    }

    protected function createReader(SplFileInfo $fileInfo): ReaderInterface
    {
        return new EntriesCSVReader($fileInfo);
    }

    protected function onSuccess(object $entity): void
    {
        $command = new SaveEntry($entity);
        $this->commandBus->handle($command);
    }

}
