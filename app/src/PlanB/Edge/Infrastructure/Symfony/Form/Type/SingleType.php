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


use PlanB\Edge\Domain\Validator\ValidatorAwareInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\AutoContainedFormTypeInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\FormAwareInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\Listener\AutoContainedFormSubscriber;
use PlanB\Edge\Infrastructure\Symfony\Form\SelfValidatedFormTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class SingleType extends AbstractType implements AutoContainedFormTypeInterface, SelfValidatedFormTypeInterface
{
    private ValidatorInterface $validator;

    public function setValidator(ValidatorInterface $validator): ValidatorAwareInterface
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return TextType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventSubscriber(new AutoContainedFormSubscriber($this));
        $builder->setByReference(false);
        $builder->setCompound(false);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->customOptions($resolver);
    }

    public function customOptions(OptionsResolver $resolver): void
    {
    }

    /**
     * @param object|null $data
     * @return object|null
     */
    public function transform(?object $data)
    {
        if (null === $data) {
            return null;
        }
        return $data;
    }

    /**
     * @param mixed $data
     * @param FormInterface $form
     */
    public function validate($data, FormInterface $form): void
    {
        $constraints = $this->getConstraints();
        if (empty($constraints)) {
            return;
        }

        $violations = $this->validator->validate($data, $constraints);
        foreach ($violations as $violation) {
            $form->addError(new FormError($violation->getMessage()));
        }
    }

    /**
     * @return null|array
     */
    abstract public function getConstraints();
}
