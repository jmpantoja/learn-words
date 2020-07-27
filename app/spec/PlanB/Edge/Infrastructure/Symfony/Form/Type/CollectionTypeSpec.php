<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form\Type;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Collection\SnapshotList;
use PlanB\Edge\Domain\Collection\TypedList;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializerInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\SingleDataMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\CollectionType;
use Prophecy\Argument;
use Sonata\AdminBundle\Form\Type\CollectionType as SonataCollectionType;
use stdClass;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionTypeSpec extends ObjectBehavior
{
    public function let(SingleDataMapperInterface $dataMapper)
    {
        $this->beAnInstanceOf(ConcreteCollectionType::class);

        $dataMapper->attach($this)->shouldBeCalled();
        $this->setDataMapper($dataMapper);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CollectionType::class);
    }

    public function it_returns_correct_parent()
    {
        $this->getParent()->shouldReturn(SonataCollectionType::class);
    }

    public function it_is_able_to_build_the_form(FormBuilderInterface $builder)
    {
        $builder->addModelTransformer(Argument::type(SingleDataMapperInterface::class))->shouldBeCalled();

        $builder->setCompound(false)->shouldBeCalled();
        $builder->setCompound(true)->shouldBeCalled();
        $builder->setByReference(false)->shouldBeCalled();

        $this->buildForm($builder, $options = []);
    }

    public function it_is_able_to_configure_options()
    {
        $data = [
            'option' => 'hola'
        ];

        $this->resolve($data)->shouldIterateAs([
            "allow_add" => true,
            "allow_delete" => true,
            "option" => "hola",
        ]);
    }

    public function it_returns_a_snapshot_list_when_normalize_a_typed_list(FormSerializerInterface $serializer)
    {
        $typedList = ConcreteTypedList::empty();
        $this->normalize($serializer, $typedList)->shouldReturnAnInstanceOf(SnapshotList::class);

        $serializer->normalize($typedList)->shouldNotBeCalled();
    }

    public function it_normalize_returns_null_when_pass_a_not_iterable_value(FormSerializerInterface $serializer)
    {
        $data = new stdClass();
        $response = null;

        $this->normalize($serializer, $data)->shouldReturn($response);
    }

    public function it_returns_same_object_when_denormalize_a_snapshot_list(FormSerializerInterface $serializer, SnapshotList $snapshotList)
    {
        $this->denormalize($serializer, $snapshotList)->shouldReturn($snapshotList);
        $serializer->normalize($snapshotList)->shouldNotBeCalled();
    }

    public function it_denormalize_returns_null_when_pass_a_not_snapshot_list(FormSerializerInterface $serializer)
    {
        $data = new stdClass();
        $response = null;

        $this->denormalize($serializer, $data)->shouldReturn($response);
    }

}

class ConcreteCollectionType extends CollectionType
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
}

class ConcreteTypedList extends TypedList
{

    public function getType(): string
    {
        return 'x';
    }
}
