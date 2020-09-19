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

    public function it_transforms_an_enum_in_a_string(){
        $this->transform(ConcreteEnum::RED())->shouldReturn('RED');
    }

    public function it_transforms_a_string_in_a_enum(){
        $this->reverse('RED')->shouldBeLike(ConcreteEnum::RED());
    }

    public function it_returns_null_when_try_to_transform_an_invalid_string(){
        $this->reverse('REDXXX')->shouldReturn(null);
    }

    public function it_does_not_have_any_constraint(){
        $this->getConstraints()->shouldBe(null);
    }
}

class ConcreteEnumType extends EnumType
{

    public function getDataClass(): string
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
