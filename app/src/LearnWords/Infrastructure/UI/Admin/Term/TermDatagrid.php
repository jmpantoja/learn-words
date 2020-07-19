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

namespace LearnWords\Infrastructure\UI\Admin\Term;


use PlanB\Edge\Infrastructure\Sonata\Configurator\DatagridConfigurator;

final class TermDatagrid extends DatagridConfigurator
{

    public function attachTo(): string
    {
        return TermAdmin::class;
    }

    public function configure()
    {
        $this->addIdentifier('word');
    }
}
