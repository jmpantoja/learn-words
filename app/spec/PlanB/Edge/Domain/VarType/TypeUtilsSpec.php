<?php

namespace spec\PlanB\Edge\Domain\VarType;

use ArrayIterator;
use ArrayObject;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\VarType\TypeUtils;
use Serializable;
use stdClass;
use Throwable;

class TypeUtilsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TypeUtils::class);
    }

    public function it_is_able_to_detect_if_a_value_is_null()
    {
        $this->check(null, 'null', 'stringable');
    }

    public function it_is_able_to_detect_if_a_value_is_boolean()
    {
        $this->check(true, 'bool', 'stringable', 'scalar');
        $this->check(false, 'bool', 'stringable', 'scalar');
    }

    public function it_is_able_to_detect_if_a_value_is_integer()
    {
        $this->check(10, 'int', 'integer', 'scalar', 'long', 'numeric', 'stringable');
    }

    public function it_is_able_to_detect_if_a_value_is_float()
    {
        $this->check(10.0, 'float', 'scalar', 'double', 'numeric', 'stringable');
    }

    public function it_is_able_to_detect_if_a_value_is_a_string()
    {
        $this->check('texto', 'string', 'scalar', 'stringable');
    }

    public function it_is_able_to_detect_if_a_value_is_an_array()
    {
        $this->check(['a', 'b', 'c'], 'array', 'countable', 'iterable');
    }

    public function it_is_able_to_detect_if_a_value_is_an_object()
    {
        $this->check(new stdClass(), 'object');
    }

    public function it_is_able_to_detect_if_a_value_is_an_callable()
    {
        $this->check(fn() => '', 'callable', 'object');
    }

    public function it_is_able_to_detect_if_a_object_of_a_class_or_interface()
    {
        $this->check(new ArrayIterator(), 'object', 'countable', 'iterable', ArrayIterator::class);
        $this->check(new ArrayIterator(), 'object', 'countable', 'iterable', Serializable::class);
    }


    public function check($value, string ...$types)
    {
        $natives = [
            'null' => false,
            'resource' => false,
            'bool' => false,
            'int' => false,
            'long' => false,
            'integer' => false,
            'float' => false,
            'double' => false,
            'numeric' => false,
            'string' => false,
            'array' => false,
            'object' => false,
            'scalar' => false,
            'callable' => false,
            'countable' => false,
            'stringable' => false,
            'iterable' => false,
        ];

        foreach ($types as $type) {
            $natives[$type] = true;
        }
        foreach ($natives as $native => $response) {

            try {
                $this::isTypeOf($value, $native)
                    ->shouldReturn($response);
            } catch (Throwable $e) {
                dump($native);
                die('xxx');
            }
        }
    }
}
