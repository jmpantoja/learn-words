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


use PlanB\Edge\Domain\Dto\AbstractDto;
use PlanB\Edge\Domain\Dto\DtoInterface;
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

    public function getFormBuilder()
    {
        //    $this->formOptions['data_class'] = DtoInterface::class;

        $formBuilder = $this->getFormContractor()->getFormBuilder(
            $this->getUniqid(),
            $this->formOptions
        );

        $this->defineFormBuilder($formBuilder);
        return $formBuilder;
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

    public function setDatagridConfigurator(DatagridConfiguratorInterface $datagridConfigurator): AdminInterface
    {
        $this->datagridConfigurator = $datagridConfigurator;
        return $this;
    }

    public function toString($object)
    {
        $pieces = explode('\\', $this->getClass());
        return array_pop($pieces);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $this->formConfigurator
            ->handle($formMapper, $this->getClass(), $this->getSubject());
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $this->datagridConfigurator
            ->handle($listMapper);
    }
}


