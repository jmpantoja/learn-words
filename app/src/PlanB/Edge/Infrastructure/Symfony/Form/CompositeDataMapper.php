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

namespace PlanB\Edge\Infrastructure\Symfony\Form;


use Error;
use Exception;
use PlanB\Edge\Domain\Entity\EntityInterface;
use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderFactory;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;


final class CompositeDataMapper implements CompositeDataMapperInterface
{
    private CompositeFormTypeInterface $formType;
    private PropertyAccessorInterface $propertyAccessor;
    private FormSerializerInterface $serializer;

    public function __construct(FormSerializerInterface $serializer, PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->serializer = $serializer;
        $this->propertyAccessor = $propertyAccessor ?? PropertyAccess::createPropertyAccessor();
    }

    public function attach(CompositeFormTypeInterface $formType): self
    {
        $this->formType = $formType;
        return $this;
    }

    /**
     * @param mixed $data
     * @param FormInterface[]|\Traversable $forms
     */
    public function mapDataToForms($data, $forms): void
    {
        $empty = null === $data || [] === $data;
        if ($empty) {
            return;
        }

        if (!\is_array($data) && !\is_object($data)) {
            throw new UnexpectedTypeException($data, 'object, array or empty');
        }


        foreach ($forms as $name => $form) {
            $config = $form->getConfig();

            if (!$this->propertyAccessor->isReadable($data, $name) || !$config->getMapped()) {
                continue;
            }

            $value = $this->propertyAccessor->getValue($data, $name);

            $form->setData($value);
        }
    }

    /**
     * @inheritDoc
     * @throws \ReflectionException
     * @codeCoverageIgnore
     */
    public function mapFormsToData($forms, &$entity): void
    {
        $entity = $this->mapFormsToObject($forms, $entity);
    }

    /**
     * @param iterable $forms
     * @param object|null $entity
     * @return mixed
     */
    public function mapFormsToObject(iterable $forms, $entity = null)
    {

        $forms = iterator_to_array($forms);
        $data = $this->extractData($forms);

        if (!$this->validate($data, $forms)) {
            return null;
        }

        return $this->denormalize($data, $entity);
    }

    /**
     * @param array $forms
     * @return array
     */
    private function extractData(array $forms): array
    {
        return array_map(function (FormInterface $form) {
            $config = $form->getConfig();
            if ($config->getMapped() && $form->isSubmitted() && $form->isSynchronized() && !$form->isDisabled()) {
                return $form->getData();
            }
        }, $forms);
    }


    /**
     * @param array $data
     * @param array $forms
     * @return bool
     */
    private function validate(array $data, array $forms): bool
    {
        $violations = $this->formType->validate($data);
        foreach ($violations as $name => $violation) {
            $field = $this->propertyAccessor->getValue($forms, $violation->getPropertyPath());
            $field->addError(new FormError($violation->getMessage()));
        }

        return 0 === $violations->count();
    }

    /**
     * @param array $data
     * @param null $entity
     * @return object|null
     */
    private function denormalize(array $data, $entity = null)
    {
        $subject = $this->computeSubject($entity);
        try {
            return $this->formType->denormalize($this->serializer, $data, $subject);
        } catch (TransformationFailedException $exception) {
            throw $exception;
        } catch (Error $exception) {
            throw $exception;
        } catch (Exception $exception) {
            return $subject;
        }
    }

    /**
     * @param object|null $entity
     * @return object|null
     */
    private function computeSubject($entity): ?object
    {
        if (!($entity instanceof EntityInterface)) {
            return null;
        }

        if (null === $this->extractIdPropertyValue($entity)) {
            return null;
        }

        return $entity;
    }

    /**
     * @param EntityInterface $entity
     * @return mixed|null
     */
    private function extractIdPropertyValue(EntityInterface $entity)
    {
        $properties = (array)$entity;

        $idValue = null;
        foreach ($properties as $key => $value) {
            if (substr($key, -3, 3) === "\x00id") {
                $idValue = $value;
            }
        }

        return $idValue;
    }
}
