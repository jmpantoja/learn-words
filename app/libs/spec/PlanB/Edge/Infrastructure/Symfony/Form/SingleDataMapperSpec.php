<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form;

use ArrayObject;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Form\SingleDataMapper;
use PlanB\Edge\Infrastructure\Symfony\Form\SingleToObjectMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Validator\SingleBuilder;
use Prophecy\Argument;

class SingleDataMapperSpec extends ObjectBehavior
{
    public function let(SingleToObjectMapperInterface $objectMapper)
    {
        $this->beConstructedWith($objectMapper);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SingleDataMapper::class);
    }

    public function it_is_able_to_transform_a_value_to_string()
    {
        $this->transform(333)->shouldReturn('333');
    }

    public function it_is_able_to_transform_a_string_to_an_object(SingleToObjectMapperInterface $objectMapper)
    {
        $response = new ArrayObject([
            'value' => 'hola'
        ]);

        $objectMapper->mapValueToObject('hola')->willReturn($response);
        $this->reverseTransform('hola')->shouldReturn($response);

        $objectMapper
            ->buildConstraints(Argument::type(SingleBuilder::class), Argument::type('array'))
            ->shouldHaveBeenCalled();
    }
}
