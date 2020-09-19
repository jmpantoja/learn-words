<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form\Type;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Collection\SnapshotList;
use PlanB\Edge\Domain\Collection\TypedList;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializerInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\Listener\AutoContainedFormSubscriber;
use PlanB\Edge\Infrastructure\Symfony\Form\SingleDataMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\CollectionType;
use Prophecy\Argument;
use Sonata\AdminBundle\Form\Type\CollectionType as SonataCollectionType;
use stdClass;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionTypeSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ConcreteCollectionType::class);
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
        $builder->addEventSubscriber(Argument::type(AutoContainedFormSubscriber::class))->shouldBeCalled();

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

    public function it_returns_a_snapshot_list_when_transform_an_iterable()
    {
        $typedList = ConcreteTypedList::empty();
        $this->transform($typedList)->shouldReturnAnInstanceOf(SnapshotList::class);
    }

    public function it_returns_a_snapshot_list_when_transform_a_not_iterable()
    {
        $this->transform('otra-cosa')->shouldReturn(null);
    }

    public function it_returns_the_right_constraints()
    {
        $this->getConstraints()->shouldReturn(null);
    }

    public function it_is_able_to_reverse_data(SnapshotList $snapshotList)
    {
        $this->reverse($snapshotList)->shouldReturn($snapshotList);
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
