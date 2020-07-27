<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form\Type;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Form\CompositeDataMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializerInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\CompositeType;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class CompositeTypeSpec extends ObjectBehavior
{
    public function let(CompositeDataMapperInterface $dataMapper)
    {
        $this->beAnInstanceOf(ConcreteCompositeType::class);

        $dataMapper->attach($this)->shouldBeCalled();
        $this->setDataMapper($dataMapper);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CompositeType::class);
    }

    public function it_is_able_to_build_the_form(FormBuilder $builder)
    {

        $builder->add('field', Argument::cetera())->shouldBeCalled();
        $builder->setDataMapper(Argument::type(CompositeDataMapperInterface::class))->shouldBeCalled();
        $builder->setByReference(false)->shouldBeCalled();
        $builder->setCompound(true)->shouldBeCalled();

        $this->buildForm($builder, $options = []);
    }

    public function it_is_able_to_configure_options()
    {
        $data = [
            'option' => 'hola'
        ];

        $this->resolve($data)->shouldIterateAs([
            "option" => "hola"
        ]);
    }

    public function it_is_able_to_denormalize_a_value(FormSerializerInterface $serializer)
    {
        $data = ['key' => 'value'];
        $response = new stdClass();
        $subject = new stdClass();

        $serializer->denormalize($data, $subject, ConcreteCompositeType::class)->willReturn($response);

        $this->denormalize($serializer, $data, $subject)->shouldReturn($response);

        $serializer->denormalize($data, $subject, ConcreteCompositeType::class)
            ->shouldHaveBeenCalled();
    }

    public function it_is_able_to_normalize_a_value(FormSerializerInterface $serializer)
    {
        $data = ['key' => 'value'];
        $response = new stdClass();

        $serializer->normalize($data)->willReturn($response);
        $this->normalize($serializer, $data)->shouldReturn($response);

        $serializer->normalize($data)
            ->shouldHaveBeenCalled();
    }

    public function it_throws_an_exception_when_denormalize_fails(FormSerializerInterface $serializer)
    {
        $data = ['key' => 'value'];
        $original = Argument::any();

        $serializer->denormalize(Argument::cetera())->willThrow(\Exception::class);

        $this->shouldThrow(\Exception::class)->during('denormalize', [$serializer, $data, $original]);

        $serializer->denormalize($data, $original, ConcreteCompositeType::class)
            ->shouldHaveBeenCalled();
    }
}

class ConcreteCompositeType extends CompositeType
{

    public function validate(array $data): ConstraintViolationListInterface
    {
        return new ConstraintViolationList();
    }

    public function customForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('field');
    }

    function customOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('option');
    }

    public function resolve($options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        return $resolver->resolve($options);
    }

    public function getClass(): string
    {
        return static::class;
    }

    /**
     * @inheritDoc
     */
    public function denormalize(FormSerializerInterface $serializer, $data, ?object $subject = null)
    {
        return $serializer->denormalize($data, $subject, $this->getClass());
    }
}
