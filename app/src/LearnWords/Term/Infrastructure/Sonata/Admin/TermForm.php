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

use LearnWords\Term\Domain\Model\Term;
use LearnWords\Term\Domain\TermBuilder;
use LearnWords\Term\Infrastructure\Symfony\Form\Type\WordType;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfigurator;
use PlanB\Edge\Infrastructure\Sonata\Doctrine\ManagerCommandFactoryInterface;
use PlanB\Edge\Infrastructure\Sonata\Doctrine\ModelManager;
use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderFactory;
use Sonata\AdminBundle\Form\Type\ModelType;


final class TermForm extends FormConfigurator
{
    /**
     * @var ModelManager
     */
    private ModelManager $modelManager;

    public function __construct(ModelManager $modelManager)
    {
        $this->modelManager = $modelManager;
    }

    public function attachTo(): string
    {
        return TermAdmin::class;
    }

    public function configure(Term $term = null)
    {
        $this
            ->add('word', WordType::class)
            ->add('tags', ModelType::class, [
                'by_reference' => false,
                'multiple' => true,
                'property' => 'tag',
            ]);
    }

}
