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

namespace PlanB\Edge\Infrastructure\Symfony\Form\Listener;

use PlanB\Edge\Infrastructure\Symfony\Form\AutoContainedFormTypeInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\SelfValidatedFormTypeInterface;
use ReflectionObject;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

final class AutoContainedFormSubscriber implements EventSubscriberInterface
{
    private AutoContainedFormTypeInterface $formType;

    /**
     * AutoContainedFormSubscriber constructor.
     * @param AutoContainedFormTypeInterface|null $formType
     */
    public function __construct(AutoContainedFormTypeInterface $formType = null)
    {
        if (null === $formType) {
            return;
        }

        $this->formType = $formType;
    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'setData',
            FormEvents::POST_SUBMIT => 'postSubmit'
        ];
    }

    /**
     * @param PreSetDataEvent $event
     */
    public function setData(PreSetDataEvent $event): void
    {
        $data = $this->formType->transform($event->getData());
        $event->setData($data);
    }

    /**
     * @param PostSubmitEvent $event
     * @throws \ReflectionException
     */
    public function postSubmit(PostSubmitEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        $this->validate($data, $form);
        $this->applyModelData($data, $form);

        $event->setData($data);
    }


    /**
     * @param mixed $data
     * @param FormInterface $form
     */
    private function validate($data, FormInterface $form): void
    {
        if (!$this->formType instanceof SelfValidatedFormTypeInterface) {
            return;
        }

        $this->formType->validate($data, $form);
    }

    /**
     * @param mixed $data
     * @param FormInterface $form
     * @throws \ReflectionException
     */
    private function applyModelData($data, FormInterface $form): void
    {
        $modelData = null;
        if ($form->isValid()) {
            $modelData = $this->formType->reverse($data);
        }

        $reflection = new ReflectionObject($form);
        $property = $reflection->getProperty('modelData');
        $property->setAccessible(true);
        $property->setValue($form, $modelData);

    }
}
