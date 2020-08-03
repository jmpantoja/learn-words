<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form\Type;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializerInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\SingleDataMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\SingleType;
use stdClass;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SingleTypeSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ConcreteSingleType::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SingleType::class);
    }

    public function it_is_able_to_build_the_form(FormBuilderInterface $builder)
    {
        $builder->addModelTransformer($this)->shouldBeCalled();
        $builder->setCompound(false)->shouldBeCalled();
        $builder->setByReference(false)->shouldBeCalled();

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

    public function it_returns_the_correct_parent()
    {
        $this->getParent()->shouldReturn(TextType::class);
    }

    public function it_transform_a_model_in_a_value()
    {
        $subject = new stdClass();
        $this->transform($subject)->shouldReturn('cadena');
    }

    public function it_returns_null_when_value_is_null()
    {
        $this->transform(null)->shouldReturn(null);
    }

    public function it_transform_a_value_in_a_model()
    {
        $this->reverseTransform('cadena')->shouldBeLike('cadena');
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

    protected function toValue($data)
    {
        return 'cadena';
    }
}
