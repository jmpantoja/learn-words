<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form\Type;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Form\CompositeDataMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\CompositeType;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
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

        $this->buildForm($builder, $options = []);
    }

    public function it_is_able_to_configure_options()
    {
        $data = [
            'option' => 'hola'
        ];

        $this->resolve($data)->shouldIterateAs([
            "compound" => true,
            "by_reference" => false,
            "option" => "hola"
        ]);
    }

    public function it_is_able_to_denormalize_a_value(DenormalizerInterface $denormalizer)
    {
        $data = ['key' => 'value'];
        $context = [];

        $this->denormalize($denormalizer, $data, $context);

        $denormalizer->denormalize($data, ConcreteCompositeType::class, null, $context)
            ->shouldHaveBeenCalled();
    }

    public function it_returns_original_value_when_denormalize_fails(DenormalizerInterface $denormalizer)
    {
        $data = ['key' => 'value'];
        $original = Argument::any();

        $context = [
            ObjectNormalizer::OBJECT_TO_POPULATE => $original
        ];

        $denormalizer->denormalize(Argument::cetera())->willThrow(\Exception::class);

        $this->denormalize($denormalizer, $data, $context)
            ->shouldReturn($original);

        $denormalizer->denormalize($data, ConcreteCompositeType::class, null, $context)
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
}
