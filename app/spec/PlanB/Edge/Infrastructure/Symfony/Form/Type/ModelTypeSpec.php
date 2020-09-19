<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form\Type;

use Doctrine\ORM\Mapping\ClassMetadata;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\ModelType;
use Sonata\AdminBundle\Model\ModelManagerInterface;
use Sonata\DoctrineORMAdminBundle\Admin\FieldDescription;
use stdClass;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class ModelTypeSpec extends ObjectBehavior
{
    public function let(PropertyAccessorInterface $propertyAccessor)
    {
        $this->beAnInstanceOf(ConcreteModelType::class);
        $this->beConstructedWith($propertyAccessor);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ModelType::class);
    }

    public function it_builds_a_form_correctly(FormBuilderInterface $builder,
                                               ModelManagerInterface $modelManager,
                                               FieldDescription $fieldDescription)
    {
        $response = $this->resolve([
            'model_manager' => $modelManager,
            'sonata_field_description' => $fieldDescription
        ]);

        $options = $response->getWrappedObject();
        $this->buildForm($builder, $options);

        $builder->addModelTransformer($this)->shouldBeCalledOnce();
    }


    public function it_resolve_options_by_default_correctly(ModelManagerInterface $modelManager,
                                                            FieldDescription $fieldDescription)
    {
        $response = $this->resolve([
            'model_manager' => $modelManager,
            'sonata_field_description' => $fieldDescription
        ]);

        $response['model_manager']->shouldReturn($modelManager);
        $response['multiple']->shouldReturn(false);
    }

    public function it_resolve_multiple_option_correctly_when_mapping_is_one_to_one(ModelManagerInterface $modelManager,
                                                                                    FieldDescription $fieldDescription)
    {
        $fieldDescription->getMappingType()->willReturn(ClassMetadata::ONE_TO_ONE);

        $response = $this->resolve([
            'model_manager' => $modelManager,
            'sonata_field_description' => $fieldDescription
        ]);

        $response['multiple']->shouldReturn(false);
    }

    public function it_resolve_multiple_option_correctly_when_mapping_is_many_to_one(ModelManagerInterface $modelManager,
                                                                                     FieldDescription $fieldDescription)
    {
        $fieldDescription->getMappingType()->willReturn(ClassMetadata::MANY_TO_ONE);

        $response = $this->resolve([
            'model_manager' => $modelManager,
            'sonata_field_description' => $fieldDescription
        ]);

        $response['multiple']->shouldReturn(false);
    }

    public function it_resolve_multiple_option_correctly_when_mapping_is_one_to_many(ModelManagerInterface $modelManager,
                                                                                     FieldDescription $fieldDescription)
    {
        $fieldDescription->getMappingType()->willReturn(ClassMetadata::ONE_TO_MANY);

        $response = $this->resolve([
            'model_manager' => $modelManager,
            'sonata_field_description' => $fieldDescription
        ]);

        $response['multiple']->shouldReturn(true);
    }

    public function it_resolve_multiple_option_correctly_when_mapping_is_many_to_many(ModelManagerInterface $modelManager,
                                                                                      FieldDescription $fieldDescription)
    {
        $fieldDescription->getMappingType()->willReturn(ClassMetadata::MANY_TO_MANY);

        $response = $this->resolve([
            'model_manager' => $modelManager,
            'sonata_field_description' => $fieldDescription
        ]);

        $response['multiple']->shouldReturn(true);
    }

    public function it_is_able_to_transform_a_value()
    {
        $this->transform(null)->shouldReturn(null);
        $this->transform('value')->shouldReturn('value');
    }

    public function it_is_able_to_reverse_a_value()
    {
        $this->reverseTransform('value')->shouldBeAnInstanceOf(stdClass::class);
    }
}

class ConcreteModelType extends ModelType
{

    public function customOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined('sonata_field_description');
    }

    public function resolve(array $data)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        return $resolver->resolve($data);
    }

    /**
     * @inheritDoc
     */
    public function reverse($value): object
    {
        return new stdClass();
    }
}
