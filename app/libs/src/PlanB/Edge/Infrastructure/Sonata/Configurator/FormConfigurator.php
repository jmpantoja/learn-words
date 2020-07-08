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

use PlanB\Edge\Application\UseCase\SaveCommand;
use PlanB\Edge\Domain\Entity\EntityBuilder;
use PlanB\Edge\Infrastructure\Sonata\Doctrine\ManagerCommandFactoryInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\CompoundDataMapper;
use PlanB\Edge\Infrastructure\Symfony\Form\CompoundToObjectMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\CustomDataMapper;
use PlanB\Edge\Infrastructure\Symfony\Validator\CompoundBuilder;
use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderFactory;
use Sonata\AdminBundle\Form\FormMapper;

abstract class FormConfigurator implements FormConfiguratorInterface, CompoundToObjectMapperInterface
{
    private ManagerCommandFactoryInterface $commandFactory;

    private FormMapper $formMapper;

    private bool $isOpened = false;


    /**
     * @param ManagerCommandFactoryInterface $commandFactory
     */
    public function setCommandFactory(ManagerCommandFactoryInterface $commandFactory): self
    {
        $this->commandFactory = $commandFactory;
        return $this;
    }


    public function handle(FormMapper $formMapper, ?object $subject): self
    {
        $this->setCommandFactory($formMapper->getAdmin());

        $this->formMapper = $formMapper;
        $options = $this->formMapper->getFormBuilder()->getOptions();

        $dataMapper = new CompoundDataMapper($this, $options);
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

    final public function mapDataToObject(array $data, $entity = null): SaveCommand
    {
        return $this->commandFactory->saveCommand($data, $entity);
    }

    public function buildConstraints(CompoundBuilder $builder, array $options): void
    {
    }

}
