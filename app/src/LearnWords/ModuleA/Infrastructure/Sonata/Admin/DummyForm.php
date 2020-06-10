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
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use Sonata\AdminBundle\Form\FormMapper;

final class DummyForm implements FormConfiguratorInterface
{

    public function attachTo(): string
    {
        return DummyAdmin::class;
    }

    public function configure(FormMapper $formMapper, Dummy $subject = null): void
    {
        $formMapper->add('name', null, [
            'attr' => [
                'style' => 'width:400px'
            ],
            'help' => $subject->getName()
        ]);
    }
}
