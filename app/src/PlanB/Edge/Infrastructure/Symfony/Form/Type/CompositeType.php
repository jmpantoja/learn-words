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


use PlanB\Edge\Domain\Entity\Dto;
use PlanB\Edge\Domain\Validator\ValidatorAwareInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\FormAwareInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class CompositeType extends AbstractType implements DataTransformerInterface, ValidatorAwareInterface
{

    private ValidatorInterface $validator;

    public function setValidator(ValidatorInterface $validator): ValidatorAwareInterface
    {
        $this->validator = $validator;
        return $this;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this);
        $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'validateEvent']);

        $this->customForm($builder, $options);
        $builder->setByReference(false);
        $builder->setCompound(true);
    }

    abstract public function customForm(FormBuilderInterface $builder, array $options): void;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->customOptions($resolver);
    }

    abstract function customOptions(OptionsResolver $resolver): void;

    public function validateEvent(PostSubmitEvent $event): self
    {
        $this->validate($event->getData(), $event->getForm());
        return $this;
    }

    private function validate(Dto $dto, FormInterface $form): void
    {
        /** @var ConstraintViolation[] $violations */
        $violations = $this->validator->validate($dto);

        foreach ($violations as $violation) {
            $path = $violation->getPropertyPath();
            $message = $violation->getMessage();

            $form->get($path)->addError(new FormError($message));
        }
    }

    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }
        return $this->toDto($value);
    }

    /**
     * @param mixed $data
     * @return Dto
     */
    abstract public function toDto($data): Dto;

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        return $value;
    }


}
