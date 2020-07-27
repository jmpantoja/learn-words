<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form;

use LogicException;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializer;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class FormSerializerSpec extends ObjectBehavior
{
    public function let(SerializerInterface $serializer)
    {
        $serializer->beADoubleOf(NormalizerInterface::class);
        $serializer->beADoubleOf(DenormalizerInterface::class);

        $this->beConstructedWith($serializer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FormSerializer::class);
    }

    public function it_throws_an_exception_if_is_initialized_without_an_argument_type_denormalizer(SerializerInterface $other)
    {
        $other->beADoubleOf(NormalizerInterface::class);
        $this->beConstructedWith($other);

        $exception = new LogicException('Expected a serializer that also implements DenormalizerInterface.');

        $this->shouldThrow($exception)->duringInstantiation();
    }

    public function it_throws_an_exception_if_is_initialized_without_an_argument_type_normalizer(SerializerInterface $other)
    {
        $other->beADoubleOf(DenormalizerInterface::class);
        $this->beConstructedWith($other);

        $exception = new LogicException('Expected a serializer that also implements NormalizerInterface.');

        $this->shouldThrow($exception)->duringInstantiation();
    }

    public function it_is_able_to_normalize_an_object(SerializerInterface $serializer)
    {

        $data = new stdClass();
        $format = static::class;
        $response = Argument::any();

        $serializer->normalize($data, $format)->willReturn($response);

        $this->normalize($data, $format)->shouldReturn($response);

        $serializer->normalize($data, $format)->shouldBeCalledOnce();
    }

    public function it_is_able_to_denormalize_an_array(SerializerInterface $serializer)
    {

        $data = ['key' => 'value'];
        $subject = Argument::any();

        $type = static::class;
        $response = Argument::any();

        $serializer->denormalize($data, $type, null, [
            ObjectNormalizer::OBJECT_TO_POPULATE => $subject
        ])->willReturn($response);

        $this->denormalize($data, $subject, $type)->shouldReturn($response);

        $serializer->denormalize($data, $type, null, [
            ObjectNormalizer::OBJECT_TO_POPULATE => $subject
        ])->shouldBeCalledOnce();
    }

    public function it_returns_same_array_when_a_type_is_not_passed(SerializerInterface $serializer)
    {
        $data = ['key' => 'value'];
        $subject = Argument::any();
        $type = null;

        $this->denormalize($data, $subject, $type)->shouldReturn($data);

        $serializer->denormalize(Argument::cetera())->shouldNotBeCalled();
    }
}
