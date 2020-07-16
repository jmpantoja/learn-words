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


use LearnWords\Term\Domain\Model\Term;
use LearnWords\Term\Domain\Model\TermId;
use LearnWords\Term\Domain\Model\Word;
use LogicException;
use PlanB\Edge\Domain\Entity\EntityInterface;
use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderFactory;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;


final class CompositeDataMapper implements CompositeDataMapperInterface
{
    private CompositeFormTypeInterface $objectMapper;
    private PropertyAccessorInterface $propertyAccessor;
    /**
     * @var DenormalizerInterface|SerializerInterface
     */
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer, PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->setSerializer($serializer);
        $this->propertyAccessor = $propertyAccessor ?? PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param SerializerInterface $serializer
     * @return CompositeDataMapper
     */
    private function setSerializer(SerializerInterface $serializer): CompositeDataMapper
    {
        if (!$serializer instanceof DenormalizerInterface) {
            throw new LogicException('Expected a serializer that also implements DenormalizerInterface.');
        }

        $this->serializer = $serializer;
        return $this;
    }


    public function attach(CompositeFormTypeInterface $objectMapper): self
    {
        $this->objectMapper = $objectMapper;
        return $this;
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

        $entity = $this->mapFormsToObject($forms, $entity);
    }

    /**
     * @param $forms
     * @param $entity
     * @return object|void|null
     */
    public function mapFormsToObject(iterable $forms, $entity = null): ?object
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
        $violations = $this->objectMapper->validate($data);

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
        return $this->objectMapper->denormalize($this->serializer, $data, [
            ObjectNormalizer::OBJECT_TO_POPULATE => $this->computeSubject($entity)
        ]);
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

        $properties = (array)$entity;

        $idValue = null;
        foreach ($properties as $key => $value) {
            if (substr($key, -3, 3) === "\x00id") {
                $idValue = $value;
            }
        }

        if (null === $idValue) {
            return null;
        }

        return $entity;
    }
}
