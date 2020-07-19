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
use PlanB\Edge\Infrastructure\Sonata\Admin\AdminInterface;
use PlanB\Edge\Infrastructure\Sonata\Doctrine\ManagerCommandFactoryInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\CompositeDataMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\CompositeFormTypeInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\CustomDataMapper;
use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderFactory;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

abstract class FormConfigurator implements FormConfiguratorInterface, CompositeFormTypeInterface
{
    protected string $className;

    private CompositeDataMapperInterface $dataMapper;

    private FormMapper $formMapper;

    private bool $isOpened = false;

    public function setDataMapper(CompositeDataMapperInterface $dataMapper): self
    {
        $this->dataMapper = $dataMapper->attach($this);
        return $this;
    }

    public function handle(FormMapper $formMapper, ?object $subject): self
    {
        $this->formMapper = $formMapper;

        $admin = $formMapper->getAdmin();
        $formBuilder = $formMapper->getFormBuilder();

        $this->initialize($admin, $formBuilder);
        $this->configure($subject);

        return $this;
    }

    protected function initialize(AdminInterface $admin, FormBuilderInterface $formBuilder): self
    {
        $this->className = $admin->getClass();
        $formBuilder->setDataMapper($this->dataMapper);

        return $this;
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

    public function denormalize(DenormalizerInterface $serializer, $data, array $context): ?object
    {
        try {
            return $serializer->denormalize($data, $this->getClass(), null, $context);
        } catch (Throwable $throwable) {
            dump($throwable->getMessage());
            throw new TransformationFailedException($throwable->getMessage());
        }
    }

    public function getClass(): string
    {
        return $this->className;
    }

    public function validate(array $data): ConstraintViolationListInterface
    {
        return new ConstraintViolationList();
    }

}
