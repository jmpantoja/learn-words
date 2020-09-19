<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form\Type;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializerInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\Listener\AutoContainedFormSubscriber;
use PlanB\Edge\Infrastructure\Symfony\Form\SingleDataMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\SingleType;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\ValidatorBuilder;

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
        $builder->addEventSubscriber(Argument::type(AutoContainedFormSubscriber::class))->shouldBeCalled();
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

        $this->transform($subject)->shouldReturn($subject);
    }

    public function it_returns_null_when_value_is_null()
    {
        $this->transform(null)->shouldReturn(null);
    }

    public function it_transform_a_value_in_a_model()
    {
        $this->reverse('cadena')->shouldBeLike('cadena');
    }

    public function it_is_able_to_validate_data(FormInterface $form)
    {

        $validator = (new ValidatorBuilder())->getValidator();
        $this->setValidator($validator);

        $this->validate('bad', $form);

        $form->addError(Argument::type(FormError::class))
            ->shouldBeCalledOnce();
    }

    public function it_ignore_validation_when_there_are_not_constraints(FormInterface $form)
    {

        $validator = (new ValidatorBuilder())->getValidator();
        $this->setValidator($validator);
        $this->removeConstraints();

        $this->validate('bad', $form);

        $form->addError(Argument::any())
            ->shouldNotBeCalled();
    }
}

class ConcreteSingleType extends SingleType
{

    private ?Constraint $constraint;

    public function __construct()
    {
        $this->constraint = new Length([
            'min' => 4
        ]);
    }

    public function customOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('option');
        parent::customOptions($resolver);
    }

    public function resolve($options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        return $resolver->resolve($options);
    }

    public function reverse($data)
    {
        return 'cadena';
    }

    public function removeConstraints(): self
    {
        $this->constraint = null;
        return $this;
    }

    public function getConstraints()
    {
        return $this->constraint;
    }
}
