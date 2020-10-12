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
use LearnWords\Domain\Dictionary\Importer\EntityOrFail;
use LearnWords\Domain\Dictionary\Importer\ImportResolverInterface;
use LearnWords\Domain\Dictionary\Importer\Reader\ReaderInterface;
use LearnWords\Domain\Dictionary\Lang;
use LearnWords\Domain\Dictionary\Provider\QuestionImporter;
use LearnWords\Infrastructure\Domain\Dictionary\Importer\Reader\EntriesCSVReader;
use LearnWords\Infrastructure\UI\Command\Exception\InvalidInputException;
use SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class ImportCommand extends Command
{

    /**
     * @var string[]
     */
    private array $failures;


    protected function configure()
    {
        $this->addArgument('source', InputArgument::REQUIRED, 'ruta al fichero con los datos');
        $this->addOption('lang', 'l', InputOption::VALUE_OPTIONAL, 'el idioma original', 'ENGLISH');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->failures = [];
        $fileInfo = $this->resolveFileInfo($input);

        $lang = $this->resolveLang($input);
        $reader = $this->createReader($fileInfo);

        $resolver = $this->getResolver();

        $entries = $resolver->resolve($reader, $lang);

        $total = $reader->count();
        $bar = $this->buildProgressBar($input, $output);
        $bar->setMaxSteps($total);

        foreach ($entries as $entryOrFail) {
            $this->process($entryOrFail);
            $bar->setMessage($entryOrFail->getLabel());
            $bar->advance(1);
        }

        $bar->setMessage('done!');
        $bar->finish();

        $this->showErrorsList($output);

        return 0;
    }

    private function process(EntityOrFail $entryOrFail)
    {
        if (!$entryOrFail->isSuccess()) {
            $this->failures[] = sprintf('%s=> %s', ...[
                $entryOrFail->getLabel(),
                $entryOrFail->getFailureReason()
            ]);
            return;
        }

        $entity = $entryOrFail->getEntity();
        $this->onSuccess($entity);
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

    /**
     * @param InputInterface $input
     * @return EntriesCSVReader
     */
    abstract protected function createReader(SplFileInfo $fileInfo): ReaderInterface;

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

    protected function showErrorsList(OutputInterface $output)
    {
        $rows = array_map(function (string $failure) {
            return [$failure];
        }, $this->failures);

        $table = new Table($output);
        $table
            ->setHeaders(['Failures'])
            ->setRows($rows);

        $table->render();
    }

    abstract protected function onSuccess(object $entity): void;

    abstract protected function getResolver(): ImportResolverInterface;
}
