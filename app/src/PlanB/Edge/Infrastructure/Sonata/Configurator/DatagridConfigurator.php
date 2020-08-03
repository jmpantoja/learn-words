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


    public function handle(ListMapper $listMapper): self
    {
        $this->listMapper = $listMapper;
        $this->configure();
        return $this;
    }

    /**
     * @param string $name
     * @param string|null $type
     * @param mixed[] $fieldDescriptionOptions
     * @return $this
     */
    public function addIdentifier(string $name, string $type = null, array $fieldDescriptionOptions = []): self
    {
        $this->listMapper->addIdentifier($name, $type, $fieldDescriptionOptions);
        return $this;
    }

    /**
     * @param string $name
     * @param string|null $type
     * @param mixed[] $fieldDescriptionOptions
     * @return $this
     */
    public function add(string $name, string $type = null, array $fieldDescriptionOptions = []): self
    {
        $this->listMapper->add($name, $type, $fieldDescriptionOptions);
        return $this;
    }
}
