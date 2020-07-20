<?php

namespace spec\PlanB\Edge\Infrastructure\Sonata\Configurator;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Entity\EntityBuilder;
use PlanB\Edge\Infrastructure\Sonata\Admin\AdminInterface;
use PlanB\Edge\Infrastructure\Sonata\Configurator\ConfiguratorInterface;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfigurator;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use PlanB\Edge\Infrastructure\Sonata\Doctrine\ManagerCommandFactoryInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\CompositeDataMapperInterface;
use Prophecy\Argument;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class FormConfiguratorSpec extends ObjectBehavior
{
    public function let(CompositeDataMapperInterface $dataMapper,
                        FormMapper $formMapper,
                        AdminInterface $admin,
                        FormBuilderInterface $formBuilder)
    {
        $dataMapper->attach(Argument::any())->willReturn($dataMapper);

        $formMapper->getAdmin()->willReturn($admin);
        $formMapper->getFormBuilder()->willReturn($formBuilder);

        $formMapper->hasOpenTab()->willReturn(false);
        $formMapper->with('tab', Argument::any())->willReturn();
        $formMapper->with('group', Argument::any())->willReturn();
        $formMapper->add('name', TextType::class, Argument::type('array'), Argument::type('array'))
            ->willReturn();

        $admin->getClass()->willReturn('className');
        $formBuilder->getOptions()->willReturn([]);

        $this->beAnInstanceOf(ConcreteFormConfigurator::class);
        $this->setDataMapper($dataMapper);

    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FormConfigurator::class);
        $this->shouldHaveType(FormConfiguratorInterface::class);
        $this->shouldHaveType(ConfiguratorInterface::class);
    }

    public function it_is_able_to_add_a_field_to_a_form_mapper(FormMapper $formMapper,
                                                               FormBuilderInterface $formBuilder)
    {
        $this->handle($formMapper, null);


        $formBuilder->setDataMapper(Argument::type(CompositeDataMapperInterface::class))
            ->shouldHaveBeenCalledOnce();

        $formMapper->add('name', TextType::class, Argument::type('array'), Argument::type('array'))
            ->shouldHaveBeenCalledOnce();

        $formMapper->with('tab', Argument::any())
            ->shouldHaveBeenCalledOnce();

        $formMapper->with('group', Argument::any())
            ->shouldHaveBeenCalledOnce();
    }

    public function it_is_able_to_denormalize_data(DenormalizerInterface $denormalizer)
    {
        $data = [];
        $context = [];
        $this->denormalize($denormalizer, $data, $context);
        $denormalizer->denormalize($data, 'className', null, $context)->shouldBeCalled();
    }
}

class ConcreteFormConfigurator extends FormConfigurator
{
    public function attachTo(): string
    {
        return 'className';
    }

    public function configure(): void
    {
        $this->tab('tab');

        $this->group('group')
            ->add('name', TextType::class, [
                'label' => 'Nombre'
            ]);
    }

    public function getClass(): string
    {
        return 'className';
    }

}
