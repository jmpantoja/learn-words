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


use PlanB\Edge\Domain\Entity\EntityInterface;
use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderFactory;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


final class CompositeDataMapper implements DataMapperInterface
{
    private CompositeToObjectMapperInterface $objectMapper;
    private array $options = [];
    private PropertyAccessorInterface $propertyAccessor;
    private ValidatorInterface $validator;

    public function __construct(CompositeToObjectMapperInterface $objectMapper, array $options = [], PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->objectMapper = $objectMapper;
        $this->options = $options;
        $this->propertyAccessor = $propertyAccessor ?? PropertyAccess::createPropertyAccessor();
//        $this->validator = (new ValidatorBuilder())->getValidator();
    }

    /**
     * @inheritDoc
     */
    public function mapDataToForms($data, $forms)
    {
        $empty = null === $data || [] === $data;
        if ($empty) {
            return;
        }

        if (!$empty && !\is_array($data) && !\is_object($data)) {
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
     */
    public function mapFormsToData($forms, &$entity)
    {
        if (null === $entity) {
            return;
        }

        $forms = iterator_to_array($forms);
        $entity = $this->process($forms, $entity);
    }

    /**
     * @param array $forms
     * @param mixed $entity
     * @return object|null
     */
    public function process(array $forms, $entity = null)
    {
        $data = $this->extractData($forms);

        if (!$this->validate($data, $forms)) {
            return null;
        }

        $subject = $this->computeSubject($entity);

        return $this->objectMapper->mapDataToObject($data, $subject);
    }

    /**
     * @param array $forms
     * @return array
     */
    private function extractData(array $forms): array
    {
        $data = array_map(function (FormInterface $form) {
            $config = $form->getConfig();
            if ($config->getMapped() && $form->isSubmitted() && $form->isSynchronized() && !$form->isDisabled()) {
                return $form->getData();
            }
        }, $forms);

        return $data;
    }


    /**
     * @param array $data
     * @param array $forms
     * @return bool
     */
    private function validate(array $data, array $forms): bool
    {
        $violations = $this->objectMapper->validate($data);

        foreach ($violations as $name => $violation) {
            $field = $this->propertyAccessor->getValue($forms, $violation->getPropertyPath());
            $field->addError(new FormError($violation->getMessage()));
        }

        return 0 === $violations->count();
    }

    /**
     * @param null $entity
     * @return object|null
     */
    private function computeSubject($entity = null): ?object
    {
        if (!($entity instanceof EntityInterface)) {
            return null;
        }

        if (null === $entity->id()) {
            return null;
        }

        return $entity;
    }
}
