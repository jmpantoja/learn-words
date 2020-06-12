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

namespace LearnWords\ModuleA\Infrastructure\Sonata\Admin;

use LearnWords\ModuleA\Domain\Dummy;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfigurator;

final class DummyForm extends FormConfigurator
{
    public function attachTo(): string
    {
        return DummyAdmin::class;
    }

    public function configure(Dummy $subject = null): void
    {
        $this->add('name', null, [
            'attr' => [
                'style' => 'width:400px'
            ],
            'help' => $subject instanceof Dummy ? $subject->getName() : 'nuevo'
        ]);
    }
}
