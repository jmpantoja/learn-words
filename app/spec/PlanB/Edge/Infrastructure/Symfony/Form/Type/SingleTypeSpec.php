<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form\Type;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Form\SingleDataMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\SingleType;
use Prophecy\Argument;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class SingleTypeSpec extends ObjectBehavior
{
    public function let(SingleDataMapperInterface $dataMapper)
    {
        $this->beAnInstanceOf(ConcreteSingleType::class);

        $dataMapper->attach($this)->shouldBeCalled();
        $this->setDataMapper($dataMapper);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SingleType::class);
    }

    public function it_is_able_to_build_the_form(FormBuilderInterface $builder)
    {
        $builder->addModelTransformer(Argument::type(SingleDataMapperInterface::class))->shouldBeCalled();

        $this->buildForm($builder, $options = []);
    }

    public function it_is_able_to_configure_options()
    {
        $data = [
            'option' => 'hola'
        ];

        $this->resolve($data)->shouldIterateAs([
            "compound" => false,
            "by_reference" => false,
            "option" => "hola"
        ]);
    }

    public function it_returns_the_correct_parent()
    {
        $this->getParent()->shouldReturn(TextType::class);
    }

    public function it_is_able_to_denormalize_a_value(DenormalizerInterface $denormalizer)
    {
        $response = Argument::any();
        $data = ['key' => 'value'];
        $context = [];

        $denormalizeCall = $denormalizer->denormalize($data, ConcreteSingleType::class, null, $context);
        $denormalizeCall->willReturn($response);

        $this->denormalize($denormalizer, $data, $context)
            ->shouldReturn($response);

        $denormalizeCall->shouldHaveBeenCalled();
    }

    public function it_returns_a_constraint_violantios_list_when_validate()
    {
        $this->validate($data = [])->shouldBeAnInstanceOf(ConstraintViolationListInterface::class);
    }
}

class ConcreteSingleType extends SingleType
{

    public function customOptions(OptionsResolver $resolver): void
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
