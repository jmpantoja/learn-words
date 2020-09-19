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

namespace PlanB\Edge\Infrastructure\Symfony\Form\Type;


use PlanB\Edge\Domain\PropertyExtractor\PropertyExtractor;
use PlanB\Edge\Domain\Validator\ValidatorAwareInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\AutoContainedFormTypeInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\FormAwareInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\Listener\AutoContainedFormSubscriber;
use PlanB\Edge\Infrastructure\Symfony\Form\SelfValidatedFormTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class CompositeType extends AbstractType implements AutoContainedFormTypeInterface, SelfValidatedFormTypeInterface
{
    private ValidatorInterface $validator;

    public function setValidator(ValidatorInterface $validator): ValidatorAwareInterface
    {
        $this->validator = $validator;
        return $this;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventSubscriber(new AutoContainedFormSubscriber($this));
        $builder->setByReference(false);
        $builder->setCompound(true);

        $this->customForm($builder, $options);
    }

    abstract public function customForm(FormBuilderInterface $builder, array $options): void;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->customOptions($resolver);
    }

    public function customOptions(OptionsResolver $resolver): void
    {
    }

    /**
     * @return array|null
     */
    public function getConstraints()
    {
        return null;
    }

    /**
     * @param object|null $data
     * @return array|null
     */
    public function transform(?object $data)
    {
        if (null === $data) {
            return null;
        }

        return PropertyExtractor::fromObject($data)->toArray();
    }

    /**
     * @param mixed $data
     * @param FormInterface $form
     */
    public function validate($data, FormInterface $form): void
    {
        $constraints = $this->getConstraints();
        $violations = $this->validator->validate($data, $constraints);

        foreach ($violations as $violation) {
            $this->addError($form, $violation);
        }
    }

    /**
     * @param FormInterface $form
     * @param ConstraintViolation $violation
     */
    private function addError(FormInterface $form, ConstraintViolation $violation): void
    {
        $propertyAccess = new PropertyAccessor();
        $child = $propertyAccess->getValue($form, $violation->getPropertyPath());
        $child->addError(new FormError($violation->getMessage()));
    }
}
