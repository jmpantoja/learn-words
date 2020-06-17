<?php

namespace spec\PlanB\Edge\Domain\Entity;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Entity\EntityBuilder;
use PlanB\Edge\Domain\Entity\EntityInterface;
use PlanB\Edge\Domain\Entity\Exception\EntityBuilderException;

class EntityBuilderSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ConcreteEntityBuilder::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(EntityBuilder::class);
    }

    public function it_is_able_to_sets_attributes_automatically()
    {
        $this->withData([
            'name' => 'nombre',
        ]);

        $this->name()->shouldBe('nombre');
    }

    public function it_throws_an_exception_when_a_property_is_not_defined()
    {
        $this->shouldThrow(EntityBuilderException::class)
            ->duringWithData([
                'bad-property' => 'nombre'
            ]);
    }

    public function it_throws_an_exception_when_some_value_is_not_valid()
    {
        $this->shouldThrow(EntityBuilderException::class)
            ->duringWithData([
                'amount' => 'string'
            ]);
    }

}

class ConcreteEntityBuilder extends EntityBuilder
{
    public string $name;
    public int $amount;

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->name;
    }

    public function build(): EntityInterface
    {
    }
}
