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
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationList;

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
        $formMapper->hasOpenTab()->willReturn(true, false);
        $this->handle($formMapper, null);

        $formBuilder->setDataMapper(Argument::type(CompositeDataMapperInterface::class))
            ->shouldHaveBeenCalledOnce();

        $formMapper->add('name', TextType::class, Argument::type('array'), Argument::type('array'))
            ->shouldHaveBeenCalledOnce();

        $formMapper->add('lastName1', TextType::class, Argument::type('array'), Argument::type('array'))
            ->shouldHaveBeenCalledOnce();

        $formMapper->add('lastName2', TextType::class, Argument::type('array'), Argument::type('array'))
            ->shouldHaveBeenCalledOnce();

        $formMapper->with(Argument::containingString('tab'), Argument::any())
            ->shouldHaveBeenCalled(2);

        $formMapper->with('group', Argument::any())
            ->shouldHaveBeenCalledTimes(3);

        $formMapper->end()
            ->shouldHaveBeenCalledTimes(2);


    }

    public function it_is_able_to_denormalize_data(DenormalizerInterface $denormalizer)
    {
        $data = [];
        $context = [];

        $this->denormalize($denormalizer, $data, $context);
        $denormalizer->denormalize($data, 'className', null, $context)->shouldBeCalled();
    }

    public function it_throws_an_exception_when_denormalize_fails(DenormalizerInterface $denormalizer)
    {
        $data = [];
        $context = [];
        $denormalizer->denormalize(Argument::any(), Argument::any(), Argument::any(), Argument::any())
            ->willThrow(\Exception::class);

        $this->shouldThrow(TransformationFailedException::class)->during('denormalize', [$denormalizer, $data, $context]);

        $denormalizer->denormalize($data, 'className', null, $context)->shouldBeCalled();
    }

//    public function it_returns_the_className(){
//        $this->getClass()
//            ->shouldReturn('className');
//    }


    public function it_returns_a_constraint_violations_list(){
        $this->validate($data = [])
            ->shouldBeAnInstanceOf(ConstraintViolationList::class);
    }
}

class ConcreteFormConfigurator extends FormConfigurator
{
    protected string $className = 'className';

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

        $this->tab('tab2');

        $this->group('group')
            ->add('lastName1', TextType::class, [
                'label' => 'Primer Apellido'
            ]);

        $this->group('group')
            ->add('lastName2', TextType::class, [
                'label' => 'Segundo Apellido'
            ]);

    }
}
