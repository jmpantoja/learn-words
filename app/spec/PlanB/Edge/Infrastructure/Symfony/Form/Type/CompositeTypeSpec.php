<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form\Type;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Dto\Dto;
use PlanB\Edge\Infrastructure\Symfony\Constraints\Collection;
use PlanB\Edge\Infrastructure\Symfony\Form\CompositeDataMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializerInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\Listener\AutoContainedFormSubscriber;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\CompositeType;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class CompositeTypeSpec extends ObjectBehavior
{
    public function let(ValidatorInterface $validator, ConstraintViolationList $violationList)
    {
        $this->beAnInstanceOf(ConcreteCompositeType::class);

        $validator->validate(Argument::cetera())->willReturn($violationList);
        $this->setValidator($validator);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CompositeType::class);
    }

    public function it_is_able_to_build_the_form(FormBuilder $builder)
    {

        $builder->add('field', Argument::cetera())->shouldBeCalled();
        $builder->addEventSubscriber(Argument::type(AutoContainedFormSubscriber::class))->shouldBeCalled();


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

    public function it_is_able_to_transform_a_value()
    {
        $subject = new stdClass();
        $this->transform($subject)->shouldBeLike([]);
    }

    public function it_is_able_to_reverse_transform_a_value()
    {
        $subject = new stdClass();
        $this->reverse($subject)->shouldReturn($subject);
    }

    public function it_returns_null_when_transform_null()
    {
        $this->transform(null)->shouldReturn(null);
    }

    public function it_is_able_to_validate_data(FormInterface $form, FormInterface $child)
    {
        $form->offsetExists('field')->willReturn(true);
        $form->offsetGet('field')->willReturn($child);

        $validator = (new ValidatorBuilder())->getValidator();
        $this->setValidator($validator);
        $this->validate([
            'field' => 'bad'
        ], $form);

        $child->addError(Argument::type(FormError::class))
            ->shouldBeCalledOnce();
    }
}

class ConcreteCompositeType extends CompositeType
{
    public function customForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('field');
    }

    function customOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('option');
        parent::customOptions($resolver);
    }

    public function getConstraints()
    {
        return parent::getConstraints() ?? new ConcreteConstraint();
    }


    public function resolve($options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        return $resolver->resolve($options);
    }


    public function reverse($data)
    {
        return $data;
    }
}

class ConcreteConstraint extends Collection
{
    public function ignoreWhen($value): bool
    {
        return false;
    }

    protected function getConstraints(): array
    {
        return  [
            'field' => new Length([
                'min' => 4
            ])
        ];
    }
}

class DummyDto extends Dto
{

    public function update($entity): object
    {
    }

    public function create(): object
    {
    }
}
