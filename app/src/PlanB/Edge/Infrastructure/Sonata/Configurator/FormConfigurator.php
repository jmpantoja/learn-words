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

use PlanB\Edge\Domain\Dto\AbstractDto;
use PlanB\Edge\Domain\Dto\DtoInterface;
use PlanB\Edge\Domain\Entity\EntityBuilder;
use PlanB\Edge\Domain\PropertyExtractor\PropertyExtractor;
use PlanB\Edge\Domain\VarType\Exception\InvalidTypeException;
use PlanB\Edge\Infrastructure\Sonata\Doctrine\ManagerCommandFactoryInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\CustomDataMapper;
use PlanB\Edge\Infrastructure\Symfony\Form\Listener\AutoContainedFormSubscriber;
use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderFactory;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

abstract class FormConfigurator implements FormConfiguratorInterface
{
    private DenormalizerInterface $serializer;

    private FormMapper $formMapper;

    private bool $isOpened = false;

    private ?object $subject;

    private string $dataClass;


    /**
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer): void
    {
        if(!($serializer instanceof DenormalizerInterface)){
            throw new InvalidTypeException($serializer, DenormalizerInterface::class);
        }

        $this->serializer = $serializer;
    }

    public function handle(FormMapper $formMapper, string $className, ?object $subjet): self
    {
        $this->formMapper = $formMapper;

        $this->initSubject($subjet, $className);

        $formBuilder = $formMapper->getFormBuilder();
        $formBuilder->addEventSubscriber(new AutoContainedFormSubscriber($this));

        $this->configure($this->subject);

        return $this;
    }


    /**
     * @param object|null $subject
     * @param string $className
     * @return $this
     */
    public function initSubject(?object $subject, string $className): self
    {
        $this->dataClass = $className;
        $this->subject = null;
        if (null === $subject) {
            return $this;
        }

        $hasIdentifier = PropertyExtractor::fromObject($subject)->hasIdentifier();
        if ($hasIdentifier) {
            $this->subject = $subject;
        }

        return $this;
    }

    /**
     * @return object|null
     */
    public function getSubject(): ?object
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getDataClass(): string
    {
        return $this->dataClass;
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

    public function reverse($data): ?object
    {
        return $this->serializer->denormalize($data, $this->dataClass, null, [
            ObjectNormalizer::OBJECT_TO_POPULATE => $this->subject
        ]);
    }

}
