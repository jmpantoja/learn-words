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

namespace LearnWords\Term\Infrastructure\Sonata\Admin;

use LearnWords\Term\Domain\Model\Tag;
use LearnWords\Term\Domain\TermBuilder;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfigurator;
use PlanB\Edge\Infrastructure\Sonata\Doctrine\ManagerCommandFactoryInterface;
use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderFactory;

final class TagForm extends FormConfigurator
{
    public function attachTo(): string
    {
        return TagAdmin::class;
    }

    public function configure(Tag $tag = null)
    {
        $this->add('tag');
    }
}
