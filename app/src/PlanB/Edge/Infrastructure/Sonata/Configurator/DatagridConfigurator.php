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

abstract class DatagridConfigurator implements DatagridConfiguratorInterface
{
    private ListMapper $listMapper;

    public function handle(ListMapper $listMapper){
        $this->listMapper = $listMapper;
        $this->configure();
    }

    public function addIdentifier($name, $type = null, array $fieldDescriptionOptions = []): self
    {
        $this->listMapper->addIdentifier($name, $type, $fieldDescriptionOptions);
        return $this;
    }

    public function add($name, $type = null, array $fieldDescriptionOptions = []): self
    {
        $this->listMapper->add($name, $type, $fieldDescriptionOptions);
        return $this;
    }
}
