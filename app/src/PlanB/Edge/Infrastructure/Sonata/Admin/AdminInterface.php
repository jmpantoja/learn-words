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
use Sonata\AdminBundle\Admin\AdminInterface as SonataAdminInterface;

interface AdminInterface extends SonataAdminInterface
{
    /**
     * Sets a form configurator
     *
     * @param FormConfiguratorInterface $formConfigurator
     * @return $this
     */
    public function setFormConfigurator(FormConfiguratorInterface $formConfigurator): self;


    /**
     * Sets a datagrid configurator
     *
     * @param DatagridConfiguratorInterface $datagridConfigurator
     * @return $this
     */
    public function setDatagridConfigurator(DatagridConfiguratorInterface $datagridConfigurator): self;

}
