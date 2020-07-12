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


use App\LearnWords\Term\Infrastructure\Symfony\Form\Type\BorrameType;
use LearnWords\Term\Domain\Model\Term;
use LearnWords\Term\Domain\TermBuilder;
use LearnWords\Term\Infrastructure\Symfony\Form\Type\WordType;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfigurator;
use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderFactory;

final class TermForm extends FormConfigurator
{
    public function attachTo(): string
    {
        return TermAdmin::class;
    }

    public function configure(Term $term = null)
    {
        $this->add('word', WordType::class);
    }
}
