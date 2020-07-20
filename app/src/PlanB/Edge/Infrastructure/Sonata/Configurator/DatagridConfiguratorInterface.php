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

namespace PlanB\Edge\Infrastructure\Sonata\Configurator;


use Sonata\AdminBundle\Datagrid\ListMapper;

interface DatagridConfiguratorInterface extends ConfiguratorInterface
{
    public const TYPE = 'datagrid';

    /**
     * @param ListMapper $listMapper
     * @return $this
     */
    public function handle(ListMapper $listMapper): self;
}
