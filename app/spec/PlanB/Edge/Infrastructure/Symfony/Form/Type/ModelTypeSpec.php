<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form\Type;

use Doctrine\ORM\Mapping\ClassMetadata;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\ModelType;
use Sonata\AdminBundle\Model\ModelManagerInterface;
use Sonata\DoctrineORMAdminBundle\Admin\FieldDescription;
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

    public function it_resolve_options_by_default_correctly(ModelManagerInterface $modelManager,
                                                            FieldDescription $fieldDescription)
    {
        $response = $this->resolve([
            'model_manager' => $modelManager,
            'sonata_field_description' => $fieldDescription
        ]);

        $response['model_manager']->shouldReturn($modelManager);
        $response['by_reference']->shouldReturn(false);
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
}
