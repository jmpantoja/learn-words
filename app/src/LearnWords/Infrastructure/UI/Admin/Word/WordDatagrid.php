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

namespace LearnWords\Infrastructure\UI\Admin\Word;


use PlanB\Edge\Infrastructure\Sonata\Configurator\DatagridConfigurator;

final class WordDatagrid extends DatagridConfigurator
{

    public function attachTo(): string
    {
        return WordAdmin::class;
    }

    public function configure(): void
    {
        $this->addIdentifier('word');
    }
}