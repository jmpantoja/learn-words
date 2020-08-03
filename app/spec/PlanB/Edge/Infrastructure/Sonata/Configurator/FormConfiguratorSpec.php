<?php

namespace spec\PlanB\Edge\Infrastructure\Sonata\Configurator;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Entity\Dto;
use PlanB\Edge\Domain\Entity\EntityBuilder;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Infrastructure\Sonata\Admin\Admin;
use PlanB\Edge\Infrastructure\Sonata\Configurator\ConfiguratorInterface;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfigurator;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use PlanB\Edge\Infrastructure\Sonata\Doctrine\ManagerCommandFactoryInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\CompositeDataMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializerInterface;
use Prophecy\Argument;
use Sonata\AdminBundle\Form\FormMapper;
use stdClass;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class FormConfiguratorSpec extends ObjectBehavior
{
    public function let(FormMapper $formMapper,
                        Admin $admin,
                        FormBuilderInterface $formBuilder)
    {


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

        $formBuilder->addModelTransformer($this)
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

    public function it_is_able_to_transform_an_entity_in_a_dto()
    {
        $this->transform(ConcreteEntity::withId())->shouldBeAnInstanceOf(Dto::class);
    }

    public function it_returns_null_when_transform_a_null_value()
    {
        $this->transform(null)->shouldReturn(null);
    }

    public function it_returns_null_when_transform_a_non_initialized_entity()
    {
        $this->transform(ConcreteEntity::withoutId())->shouldReturn(null);
    }

    public function it_is_able_to_reverse_transform_a_value()
    {
        $subject = new stdClass();
        $this->reverseTransform($subject)->shouldReturn($subject);
    }
//
//    public function it_is_able_to_denormalize_data(FormSerializerInterface $serializer)
//    {
//        $data = [];
//        $subject = new stdClass();
//        $response = Argument::any();
//
//        $serializer->denormalize($data, $subject, 'className')->willReturn($response);
//
//        $this->denormalize($serializer, $data, $subject)->shouldReturn($response);
//        $serializer->denormalize($data, $subject, 'className')->shouldBeCalled();
//    }
//
//    public function it_throws_an_exception_when_denormalize_fails(FormSerializerInterface $serializer)
//    {
//        $data = [];
//        $subject = new stdClass();
//
//        $serializer->denormalize(Argument::any(), Argument::any(), Argument::any())
//            ->willThrow(\Exception::class);
//
//        $this->shouldThrow(TransformationFailedException::class)->during('denormalize', [$serializer, $data, $subject]);
//
//        $serializer->denormalize($data, $subject, 'className')->shouldBeCalled();
//    }
//
//    public function it_returns_a_constraint_violations_list()
//    {
//        $this->validate($data = [])
//            ->shouldBeAnInstanceOf(ConstraintViolationList::class);
//    }
}

class ConcreteFormConfigurator extends FormConfigurator
{
    protected string $dataClass = 'className';

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

    protected function toDto($entity): Dto
    {
        return new ConcreteDto();
    }
}

class ConcreteDto extends Dto
{

    public function update($entity): object
    {
        // TODO: Implement update() method.
    }

    public function create(): object
    {
        // TODO: Implement create() method.
    }
}

class ConcreteEntity
{
    private EntityId  $id;

    static public function withId(): self
    {
        return new static(new EntityId());
    }

    static public function withoutId(): self
    {
        return new static();
    }

    private function __construct(EntityId $id = null)
    {
        if (null === $id) {
            return;
        }
        $this->id = $id;

    }

}
