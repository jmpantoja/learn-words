<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form\Type;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Enum\Enum;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializerInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\EnumType;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnumTypeSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ConcreteEnumType::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(EnumType::class);
    }

    public function it_returns_correct_parent()
    {
        $this->getParent()->shouldReturn(ChoiceType::class);
    }

    public function it_is_able_to_configure_options_correctely()
    {
        $this->resolve()
            ->shouldIterateAs([
                "enum_class" => ConcreteEnum::class,
                "choices" => [
                    "red" => "RED",
                    "green" => "GREEN",
                    "blue" => "BLUE"
                ]
            ]);
    }

    public function it_is_able_to_validate_a_value()
    {
        $this->validate('RED')->count()
            ->shouldReturn(0);

        $this->validate('BAD_VALUE')->count()
            ->shouldReturn(1);
    }

    public function it_delegate_in_serializer_data_normalization(FormSerializerInterface $serializer){
        $data = new stdClass();
        $response = Argument::any();

        $serializer->normalize($data)->willReturn($response);
        $this->normalize($serializer, $data)->shouldReturn($response);
    }

    public function it_delegate_in_serializer_data_denormalization(FormSerializerInterface $serializer){
        $data = new stdClass();
        $response = ConcreteEnum::RED();

        $serializer->denormalize($data, Argument::cetera())->willReturn($response);
        $this->denormalize($serializer, $data)->shouldReturn($response);
    }
}

class ConcreteEnumType extends EnumType
{

    public function getClass(): string
    {
        return ConcreteEnum::class;
    }

    public function resolve($options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        return $resolver->resolve($options);

    }
}

class ConcreteEnum extends Enum
{
    private const RED = 'red';
    private const GREEN = 'green';
    private const BLUE = 'blue';
}
