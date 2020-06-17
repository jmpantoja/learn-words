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
use ReflectionProperty;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class DataMapper implements DataMapperInterface
{
    private PropertyAccessorInterface $propertyAccessor;

    private EntityBuilder $builder;

    public function __construct(EntityBuilder $builder, PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->builder = $builder;
        $this->propertyAccessor = $propertyAccessor ?? PropertyAccess::createPropertyAccessor();
    }


    /**
     * @inheritDoc
     */
    public function mapDataToForms($data, $forms)
    {
        $empty = null === $data || [] === $data;

        if (!$empty && !\is_array($data) && !\is_object($data)) {
            throw new UnexpectedTypeException($data, 'object, array or empty');
        }

        foreach ($forms as $form) {
            $propertyPath = $form->getPropertyPath();
            $config = $form->getConfig();

            if (!$empty && null !== $propertyPath && $config->getMapped()) {
                $form->setData($this->propertyAccessor->getValue($data, $propertyPath));
            } else {
                $form->setData($config->getData());
            }
        }
    }

    /**
     * @inheritDoc
     * @throws \ReflectionException
     */
    public function mapFormsToData($forms, &$entity)
    {
        if (null === $entity) {
            return;
        }

        if (!($entity instanceof EntityInterface)) {
            throw new UnexpectedTypeException($entity, EntityInterface::class);
        }

        $data = [];

        foreach ($forms as $form) {
            $propertyPath = $form->getPropertyPath();

            $config = $form->getConfig();

            if (null !== $propertyPath && $config->getMapped() && $form->isSubmitted() && $form->isSynchronized() && !$form->isDisabled()) {
                $propertyValue = $form->getData();
                if ($propertyValue instanceof \DateTimeInterface && $propertyValue == $this->propertyAccessor->getValue($data, $propertyPath)) {
                    continue;
                }

                if (!\is_object($data) || !$config->getByReference() || $propertyValue !== $this->propertyAccessor->getValue($data, $propertyPath)) {
                    $data[(string)$propertyPath] = $propertyValue;
                }
            }
        }

        $input = null;
        if (!is_null($entity->id())) {
            $input = $entity;
        }

        $entity = $this->builder
            ->withData($data)
            ->build($input);

    }

    /**
     * @param EntityInterface $data
     * @return bool
     * @throws \ReflectionException
     */
    private function isEmptyEntity(EntityInterface $data): bool
    {
        $attribute = new ReflectionProperty(get_class($data), 'id');
        $attribute->setAccessible(true);
        $entityId = $attribute->getValue($data);

        return null === $entityId;
    }

}
