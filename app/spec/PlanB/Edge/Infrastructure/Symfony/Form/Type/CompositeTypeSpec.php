<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form\Type;

use ArrayIterator;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Entity\Dto;
use PlanB\Edge\Infrastructure\Symfony\Form\CompositeDataMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializerInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\CompositeType;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        $builder->addModelTransformer($this)->shouldBeCalled();
        $builder->addEventListener('form.post_submit', Argument::cetera())->shouldBeCalled();

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
        $this->transform($subject)->shouldReturnAnInstanceOf(Dto::class);
    }

    public function it_is_able_to_reverse_transform_a_value()
    {
        $subject = new stdClass();
        $this->reverseTransform($subject)->shouldReturn($subject);
    }

    public function it_returns_null_when_transform_null()
    {
        $this->transform(null)->shouldReturn(null);
    }

    public function it_is_able_to_validate_a_submit_form_event(Dto $data,
                                                               FormInterface $form,
                                                               ConstraintViolationList $violationList,
                                                               ConstraintViolation $violation,
                                                               FormInterface $field
    )
    {
        $event = new PostSubmitEvent(...[
            $form->getWrappedObject(),
            $data->getWrappedObject()
        ]);

        $violation->getPropertyPath()->willReturn('key');
        $violation->getMessage()->willReturn('error message');

        $violationList->getIterator()->willReturn(new ArrayIterator([
            $violation->getWrappedObject()
        ]));

        $form->get('key')->willReturn($field);
        $this->validateEvent($event);

        $field->addError(Argument::type(FormError::class))->shouldHaveBeenCalled();


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
    }

    public function resolve($options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        return $resolver->resolve($options);
    }


    public function toDto($data): Dto
    {
        return new DummyDto();
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
