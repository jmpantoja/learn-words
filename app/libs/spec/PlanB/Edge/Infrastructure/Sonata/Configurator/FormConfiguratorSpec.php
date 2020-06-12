<?php

namespace spec\PlanB\Edge\Infrastructure\Sonata\Configurator;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfigurator;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use Prophecy\Argument;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FormConfiguratorSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ConcreteFormConfigurator::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FormConfigurator::class);
        $this->shouldHaveType(FormConfiguratorInterface::class);
    }

    public function it_is_able_to_add_a_field_to_a_form_mapper(FormMapper $formMapper)
    {
        $formMapper->hasOpenTab()->willReturn(false);
        $this->run($formMapper, null);

        $formMapper->with('tab', Argument::any())
            ->shouldHaveBeenCalledOnce();

        $formMapper->with('group', Argument::any())
            ->shouldHaveBeenCalledOnce();

        $formMapper->add('name', TextType::class, Argument::type('array'), Argument::type('array'))
            ->shouldHaveBeenCalledOnce();
    }
}

class ConcreteFormConfigurator extends FormConfigurator
{
    public function attachTo(): string
    {
        return 'className';
    }

    public function configure()
    {
        $this->tab('tab');

        $this->group('group')
            ->add('name', TextType::class, [
                'label' => 'Nombre'
            ]);
    }
}
