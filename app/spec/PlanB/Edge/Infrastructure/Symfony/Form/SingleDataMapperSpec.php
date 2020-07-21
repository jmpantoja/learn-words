<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form;

use ArrayObject;
use LogicException;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Form\Exception\SingleMappingFailedException;
use PlanB\Edge\Infrastructure\Symfony\Form\SingleDataMapper;
use PlanB\Edge\Infrastructure\Symfony\Form\SingleFormTypeInterface;
use Prophecy\Argument;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class SingleDataMapperSpec extends ObjectBehavior
{
    public function let(SerializerInterface $serializer,
                        ConstraintViolationList $constraintViolationList,
                        ConstraintViolation $constraintViolation,
                        SingleFormTypeInterface $objectMapper)
    {
        $serializer->implement(DenormalizerInterface::class);

        $this->prepareViolationsList($constraintViolationList, $constraintViolation);

        $this->beConstructedWith($serializer);
        $this->attach($objectMapper);
    }

    /**
     * @param $constraintViolation
     * @param $constraintViolationList
     */
    private function prepareViolationsList(ConstraintViolationList $constraintViolationList,
                                           ConstraintViolation $constraintViolation): void
    {
        $constraintViolation->getMessage()->willReturn('Mapper Exception');
        $constraintViolation->getMessageTemplate()->willReturn('');
        $constraintViolation->getParameters()->willReturn([]);

        $constraintViolationList->count()->willReturn(0);
        $constraintViolationList->get(0)->willReturn($constraintViolation);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SingleDataMapper::class);
    }

    public function it_throws_an_exception_if_serializer_not_implement_denormalizer_interface(SerializerInterface $otherSerializer)
    {

        $this->shouldThrow(LogicException::class)->during('setSerializer', [$otherSerializer]);
    }


    public function it_is_able_to_transform_a_value()
    {
        $this->transform(333)->shouldReturn(333);
    }

    public function it_is_able_to_transform_a_string_to_an_object(SerializerInterface $serializer,
                                                                  SingleFormTypeInterface $objectMapper)
    {
        $data = 'entrada';
        $response = new ArrayObject([
            'value' => $data
        ]);

        $validateCall = $objectMapper->validate($data);
        $denormalizeCall = $objectMapper->denormalize($serializer, $data, [
            ObjectNormalizer::OBJECT_TO_POPULATE => null
        ]);

        $validateCall->willReturn(new ConstraintViolationList());
        $denormalizeCall->willReturn($response);

        $this->reverseTransform($data)->shouldReturn($response);

        $denormalizeCall->shouldHaveBeenCalledOnce();
        $validateCall->shouldHaveBeenCalled();
    }

    public function it_throws_an_exception_when_validation_fails(ConstraintViolationList $constraintViolationList,
                                                                 SingleFormTypeInterface $objectMapper)
    {
        $data = 'entrada';

        $constraintViolationList->count()->willReturn(1);

        $validateCall = $objectMapper->validate($data);
        $denormalizeCall = $objectMapper->denormalize(Argument::any(), Argument::any(), Argument::any(),);

        $validateCall->willReturn($constraintViolationList->getWrappedObject());

        $this->shouldThrow(SingleMappingFailedException::class)
            ->during('reverseTransform', [$data]);

        $denormalizeCall->shouldNotBeCalled();
        $validateCall->shouldHaveBeenCalled();
    }


}
