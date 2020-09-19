<?php

namespace spec\PlanB\Edge\Infrastructure\Sonata\Configurator;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Dto\Dto;
use PlanB\Edge\Domain\Entity\EntityBuilder;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Domain\VarType\Exception\InvalidTypeException;
use PlanB\Edge\Infrastructure\Sonata\Configurator\ConfiguratorInterface;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfigurator;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use PlanB\Edge\Infrastructure\Sonata\Doctrine\ManagerCommandFactoryInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\CompositeDataMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializerInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\Listener\AutoContainedFormSubscriber;
use Prophecy\Argument;
use Sonata\AdminBundle\Form\FormMapper;
use stdClass;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class FormConfiguratorSpec extends ObjectBehavior
{
    public function let(FormMapper $formMapper,
                        FormBuilderInterface $formBuilder)
    {
        $formMapper->getFormBuilder()->willReturn($formBuilder);

        $formMapper->hasOpenTab()->willReturn(false);
        $formMapper->with('tab', Argument::any())->willReturn();
        $formMapper->with('group', Argument::any())->willReturn();
        $formMapper->add('name', TextType::class, Argument::cetera())
            ->willReturn();

        $formBuilder->getOptions()->willReturn([]);

        $this->beAnInstanceOf(ConcreteFormConfigurator::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FormConfigurator::class);
        $this->shouldHaveType(FormConfiguratorInterface::class);
        $this->shouldHaveType(ConfiguratorInterface::class);
    }

    public function it_is_able_to_set_the_subject_and_data_class(){
        $subject = ConcreteEntity::withId();
        $className = 'className';

        $this->initSubject($subject, $className);

        $this->getSubject()->shouldReturn($subject);
        $this->getDataClass()->shouldReturn($className);
    }

    public function it_is_able_to_set_the_subject_as_null_when_it_have_not_and_id(){
        $subject = ConcreteEntity::withoutId();
        $className = 'className';

        $this->initSubject($subject, $className);

        $this->getSubject()->shouldReturn(null);
        $this->getDataClass()->shouldReturn($className);
    }


    public function it_is_able_to_add_a_field_to_a_form_mapper(FormMapper $formMapper,
                                                               FormBuilderInterface $formBuilder)
    {
        $formMapper->hasOpenTab()->willReturn(true, false);
        $this->handle($formMapper, ConcreteEntity::class, null);

        $formBuilder->addEventSubscriber(Argument::type(AutoContainedFormSubscriber::class))
            ->shouldHaveBeenCalledOnce();

        $formMapper->add('lastName1', TextType::class, Argument::cetera())
            ->shouldHaveBeenCalledOnce();

        $formMapper->add('lastName2', TextType::class, Argument::cetera())
            ->shouldHaveBeenCalledOnce();

        $formMapper->with(Argument::containingString('tab'), Argument::cetera())
            ->shouldHaveBeenCalled(2);

        $formMapper->with('group', Argument::cetera())
            ->shouldHaveBeenCalledTimes(3);

        $formMapper->end()
            ->shouldHaveBeenCalledTimes(2);
    }

    public function it_is_able_to_transform_an_entity_in_a_dto()
    {
        $this->transform(ConcreteEntity::withId())->shouldBeAnInstanceOf(stdClass::class);
    }


    public function it_is_able_to_reverse_transform_a_value(SerializerInterface $serializer)
    {
        $subject = new stdClass();

        $serializer->beADoubleOf(DenormalizerInterface::class);
        $serializer->denormalize($subject, get_class($subject), Argument::cetera())->willReturn($subject);

        $this->setSerializer($serializer);
        $this->initSubject($subject, get_class($subject));

        $this->reverse($subject)->shouldReturn($subject);
    }

    public function it_throws_an_exception_when_serializer_is_not_denormalizer(SerializerInterface $serializer){

        $this->shouldThrow(InvalidTypeException::class)->duringSetSerializer($serializer);
    }
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

    public function transform(?object $data)
    {
        return new stdClass();
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
