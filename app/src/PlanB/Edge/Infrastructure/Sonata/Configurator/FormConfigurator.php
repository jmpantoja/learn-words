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

use PlanB\Edge\Domain\Entity\Dto;
use PlanB\Edge\Domain\Entity\EntityBuilder;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Domain\PropertyExtractor\PropertyExtractor;
use PlanB\Edge\Infrastructure\Sonata\Doctrine\ManagerCommandFactoryInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\CustomDataMapper;
use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderFactory;
use Sonata\AdminBundle\Form\FormMapper;

abstract class FormConfigurator implements FormConfiguratorInterface
{
    private FormMapper $formMapper;

    private bool $isOpened = false;

    private ?object $subject;

    public function handle(FormMapper $formMapper, ?object $subject): self
    {
        $this->formMapper = $formMapper;
        $admin = $formMapper->getAdmin();
        $formBuilder = $formMapper->getFormBuilder();

        $formBuilder->addModelTransformer($this);
        $this->configure($subject);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function transform($subject)
    {
        $subject = $this->computeSubject($subject);
        if (null === $subject) {
            return null;
        }
        return $this->toDto($subject);
    }

    /**
     * @param object|null $entity
     * @return object|null
     */
    private function computeSubject(?object $entity): ?object
    {
        if (null === $entity) {
            return null;
        }

        $idValue = PropertyExtractor::fromObject($entity)
            ->id();

        if (!($idValue instanceof EntityId)) {
            return null;
        }
        return $entity;
    }

    /**
     * @param mixed $entity
     * @return Dto
     */
    abstract protected function toDto($entity): Dto;

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        return $value;
    }

    protected function tab(string $name): self
    {
        $this->endTabs();
        $this->formMapper->with($name, ['tab' => true]);
        return $this;

    }

    private function endTabs(): void
    {
        while ($this->formMapper->hasOpenTab()) {
            $this->formMapper->end();
        }
        $this->isOpened = false;
    }

    protected function group(string $name, array $options = []): self
    {
        $this->endGroups();
        $this->formMapper->with($name, $options);
        return $this;
    }

    private function endGroups(): void
    {
        if ($this->isOpened) {
            $this->formMapper->end();
        }
        $this->isOpened = true;
    }

    /**
     * @param string $name
     * @param string|null $type
     * @param mixed[] $options
     * @param mixed[] $fieldDescriptionOptions
     * @return $this
     */
    protected function add(string $name, ?string $type = null, array $options = [], array $fieldDescriptionOptions = []): self
    {
        $this->formMapper->add($name, $type, $options, $fieldDescriptionOptions);
        return $this;
    }

}
