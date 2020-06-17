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

use PlanB\Edge\Domain\Entity\EntityBuilder;
use PlanB\Edge\Domain\Entity\EntityInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\CustomDataMapper;
use ReflectionProperty;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

abstract class FormConfigurator implements FormConfiguratorInterface
{

    private FormMapper $formMapper;

    private bool $isOpened = false;
    /**
     * @var EntityBuilder
     */
    private EntityBuilder $builder;

    public function __construct(EntityBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function handle(FormMapper $formMapper, ?object $subject): self
    {
        $this->formMapper = $formMapper;

        $dataMapper = new DataMapper($this->builder);
        $this->formMapper->getFormBuilder()->setDataMapper($dataMapper);

        $this->configure($subject);

        return $this;
    }

    /**
     * @return FormMapper
     */
    public function formMapper(): FormMapper
    {
        return $this->formMapper;
    }

    protected function tab(string $name): self
    {
        $this->endTabs();
        $this->formMapper->with($name, ['tab' => true]);
        return $this;

    }

    protected function group(string $name, array $options = []): self
    {
        $this->endGroups();
        $this->formMapper->with($name, $options);
        return $this;
    }

    protected function add($name, $type = null, array $options = [], array $fieldDescriptionOptions = []): self
    {
        $this->formMapper->add($name, $type, $options, $fieldDescriptionOptions);
        return $this;
    }

    private function endTabs(): void
    {
        while ($this->formMapper->hasOpenTab()) {
            $this->formMapper->end();
        }
        $this->isOpened = false;
    }

    private function endGroups(): void
    {
        if ($this->isOpened) {
            $this->formMapper->end();
        }
        $this->isOpened = true;
    }

}
