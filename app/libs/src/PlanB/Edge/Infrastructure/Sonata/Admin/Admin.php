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


use PlanB\Edge\Infrastructure\Sonata\Configurator\DatagridConfiguratorInterface;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use PlanB\Edge\Infrastructure\Sonata\Doctrine\ManagerCommandFactoryInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;


abstract class Admin extends AbstractAdmin implements AdminInterface
{
    private FormConfiguratorInterface $formConfigurator;

    private DatagridConfiguratorInterface $datagridConfigurator;

    /**
     * @inheritDoc
     */
    public function getBaseRouteName()
    {
        return RouteBaseUtils::fromClassName($this->getClass())
            ->getBaseRouteName();
    }

    /**
     * @inheritDoc
     */
    public function getBaseRoutePattern()
    {
        return RouteBaseUtils::fromClassName($this->getClass())
            ->getBaseRoutePattern();
    }

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
            ->handle($formMapper, $this->getSubject());
    }

    public function setDatagridConfigurator(DatagridConfiguratorInterface $datagridConfigurator): AdminInterface
    {
        $this->datagridConfigurator = $datagridConfigurator;
        return $this;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $this->datagridConfigurator
            ->handle($listMapper);
    }
}


