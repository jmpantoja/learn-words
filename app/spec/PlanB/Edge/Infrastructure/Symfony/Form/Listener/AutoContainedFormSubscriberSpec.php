<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form\Listener;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Form\AutoContainedFormTypeInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\Listener\AutoContainedFormSubscriber;
use PlanB\Edge\Infrastructure\Symfony\Form\SelfValidatedFormTypeInterface;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class AutoContainedFormSubscriberSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(AutoContainedFormSubscriber::class);
    }

    public function it_is_subscribed_to_right_events()
    {
        $this::getSubscribedEvents()->shouldHaveKey(FormEvents::PRE_SET_DATA);
        $this::getSubscribedEvents()->shouldHaveKey(FormEvents::POST_SUBMIT);
    }

    public function it_is_able_to_set_data(AutoContainedFormTypeInterface $formType, PreSetDataEvent $event)
    {
        $data = new stdClass();
        $event->getData()->willReturn();
        $formType->transform(Argument::any())->willReturn($data);

        $this->beConstructedWith($formType);

        $this->setData($event);
        $event->setData($data)->shouldHaveBeenCalled();
    }

    public function it_dont_validate_when_form_dont_have_the_right_interface(AutoContainedFormTypeInterface $formType, stdClass $form, PostSubmitEvent $event)
    {
        $data = new stdClass();
        $event->getData()->willReturn($data);
        $event->getForm()->willReturn($form);

        $form->beADoubleOf(FormInterface::class);
        $form->modelData = null;

        $this->beConstructedWith($formType);
        $this->postSubmit($event);

        $event->setData(Argument::any())->shouldBeCalled();
    }


    public function it_is_able_to_validate_data(AutoContainedFormTypeInterface $formType, stdClass $form, PostSubmitEvent $event)
    {

        $data = new stdClass();
        $event->getData()->willReturn($data);
        $event->getForm()->willReturn($form);

        $form->beADoubleOf(FormInterface::class);
        $form->modelData = null;

        $formType->beADoubleOf(SelfValidatedFormTypeInterface::class);
        $formType->reverse(Argument::any())->willReturn($data);
        $formType->validate(Argument::cetera())->willReturn();

        $form->isValid()->willReturn(true);

        $this->beConstructedWith($formType);
        $this->postSubmit($event);

        $event->setData(Argument::any())->shouldBeCalled();

    }
}

