<?php

namespace spec\PlanB\Edge\Infrastructure\Sonata\Configurator;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Entity\EntityBuilder;
use PlanB\Edge\Infrastructure\Sonata\Configurator\ConfiguratorInterface;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfigurator;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use PlanB\Edge\Infrastructure\Sonata\Doctrine\ManagerCommandFactoryInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\CompoundDataMapper;
use Prophecy\Argument;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilder;

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
        $this->shouldHaveType(ConfiguratorInterface::class);
    }

    public function it_is_able_to_add_a_field_to_a_form_mapper(FormMapper $formMapper, FormBuilder $formBuilder, ManagerCommandFactoryInterface $commandFactory)
    {
        $formBuilder->getOptions()->willReturn([]);

        $formMapper->getFormBuilder()->willReturn($formBuilder);
        $formMapper->getAdmin()->willReturn($commandFactory);

        $formMapper->hasOpenTab()->willReturn(false);

        $this->handle($formMapper, null);

        $formBuilder->setDataMapper(Argument::type(CompoundDataMapper::class))
            ->shouldHaveBeenCalledOnce();

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
