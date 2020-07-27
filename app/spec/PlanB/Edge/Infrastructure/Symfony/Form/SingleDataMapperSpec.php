<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form;

use ArrayObject;
use LogicException;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Form\Exception\SingleMappingFailedException;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializer;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializerInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\SingleDataMapper;
use PlanB\Edge\Infrastructure\Symfony\Form\SingleFormTypeInterface;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class SingleDataMapperSpec extends ObjectBehavior
{
    public function let(FormSerializerInterface $serializer,
                        ConstraintViolationList $constraintViolationList,
                        ConstraintViolation $constraintViolation,
                        SingleFormTypeInterface $formType)
    {
        $this->prepareViolationsList($constraintViolationList, $constraintViolation);

        $this->beConstructedWith($serializer);
        $this->attach($formType);
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

    public function it_is_able_to_transform_a_object_to_an_array(FormSerializerInterface $serializer,
                                                                  SingleFormTypeInterface $formType)
    {
        $data = new stdClass();
        $response = [
            'key' => 'value'
        ];

        $normalizeCall = $formType->normalize($serializer, $data);
        $normalizeCall->willReturn($response);

        $this->transform($data)->shouldReturn($response);
        $normalizeCall->shouldHaveBeenCalledOnce();
    }


    public function it_is_able_to_transform_a_string_to_an_object(FormSerializerInterface $serializer,
                                                                  SingleFormTypeInterface $formType)
    {
        $data = 'entrada';
        $response = new ArrayObject([
            'value' => $data
        ]);

        $validateCall = $formType->validate($data);
        $denormalizeCall = $formType->denormalize($serializer, $data, $subject = null);

        $validateCall->willReturn(new ConstraintViolationList());
        $denormalizeCall->willReturn($response);

        $this->reverseTransform($data)->shouldReturn($response);

        $denormalizeCall->shouldHaveBeenCalledOnce();
        $validateCall->shouldHaveBeenCalled();
    }

    public function it_throws_an_exception_when_validation_fails(ConstraintViolationList $constraintViolationList,
                                                                 SingleFormTypeInterface $formType)
    {
        $data = 'entrada';

        $constraintViolationList->count()->willReturn(1);

        $validateCall = $formType->validate($data);
        $denormalizeCall = $formType->denormalize(Argument::any(), Argument::any(), Argument::any(),);

        $validateCall->willReturn($constraintViolationList->getWrappedObject());

        $this->shouldThrow(SingleMappingFailedException::class)
            ->during('reverseTransform', [$data]);

        $denormalizeCall->shouldNotBeCalled();
        $validateCall->shouldHaveBeenCalled();
    }
}
