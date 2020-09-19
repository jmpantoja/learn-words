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


use Exception;
use League\Tactician\CommandBus;
use LearnWords\Application\Dictionary\UseCase\SaveEntry;
use LearnWords\Domain\Dictionary\Importer\ImportEntriesResolverInterface;
use LearnWords\Domain\Dictionary\Lang;
use LearnWords\Domain\Dictionary\Provider\QuestionImporter;
use LearnWords\Infrastructure\Domain\Dictionary\Importer\Reader\EntriesCSVReader;
use LearnWords\Infrastructure\UI\Command\Exception\InvalidInputException;
use SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class ImportEntriesCommand extends Command implements SerializerAwareInterface
{
    protected static $defaultName = 'import:entries';

    private CommandBus $commandBus;
    private SerializerInterface $serializer;
    /**
     * @var ImportEntriesResolverInterface
     */
    private ImportEntriesResolverInterface $resolver;

    public function __construct(ImportEntriesResolverInterface $resolver, CommandBus $commandBus)
    {
        parent::__construct(null);
        $this->resolver = $resolver;
        $this->commandBus = $commandBus;
    }


    /**
     * @inheritDoc
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }


    protected function configure()
    {
        $this->addArgument('source', InputArgument::REQUIRED, 'ruta al fichero con los datos');
        $this->addOption('lang', 'l', InputOption::VALUE_OPTIONAL, 'el idioma original', 'ENGLISH');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entries = $this->resolveEntries($input);

        $total = count($entries);
        $bar = $this->buildProgressBar($input, $output);
        $bar->setMaxSteps($total);

        foreach ($entries as $entry) {

            $command = new SaveEntry($entry);
            $this->commandBus->handle($command);

            $bar->setMessage((string)$entry->getWord());
            $bar->advance(1);
        }

        $bar->setMessage('done!');
        $bar->finish();

        return 0;
    }

    /**
     * @param InputInterface $input
     * @return mixed
     */
    protected function resolveEntries(InputInterface $input)
    {
        $lang = $this->resolveLang($input);
        $fileInfo = $this->resolveFileInfo($input);
        $reader = new EntriesCSVReader($fileInfo);
        $entries = $this->resolver->resolve($reader, $lang);
        return $entries;
    }

    private function resolveLang(InputInterface $input): Lang
    {
        $lang = $input->getOption('lang');

        try {
            return Lang::make($lang);
        } catch (Exception $e) {
            throw InvalidInputException::invalidLang($lang);
        }
    }

    private function resolveFileInfo(InputInterface $input): SplFileInfo
    {
        $source = $input->getArgument('source');
        $fileInfo = new SplFileInfo($source);

        if (!($fileInfo->isFile() && $fileInfo->isReadable())) {
            throw InvalidInputException::invalidSource($fileInfo);
        }
        return $fileInfo;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return ProgressBar
     */
    protected function buildProgressBar(InputInterface $input, OutputInterface $output): ProgressBar
    {
        $style = new SymfonyStyle($input, $output);
        ProgressBar::setFormatDefinition('custom', '%bar% %current%/%max% -- %message%');
        $bar = $style->createProgressBar();
        $bar->setFormat('custom');
        return $bar;
    }


}
