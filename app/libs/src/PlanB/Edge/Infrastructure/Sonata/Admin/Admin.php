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

namespace PlanB\Edge\Infrastructure\Sonata\Admin;


use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;

abstract class Admin extends AbstractAdmin implements AdminInterface
{
    private FormConfiguratorInterface $formConfigurator;

    /**
     * @param FormConfiguratorInterface $formConfigurator
     * @return $this
     */
    public function setFormConfigurator(FormConfiguratorInterface $formConfigurator): self
    {
        $this->formConfigurator = $formConfigurator;

        return $this;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $this->formConfigurator
            ->run($formMapper, $this->getSubject());
    }

}
