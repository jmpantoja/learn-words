<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Normalizer;

use Exception;
use LogicException;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Normalizer\Denormalizer;
use stdClass;
use Symfony\Component\Serializer\Exception\MappingException;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class DenormalizerSpec extends ObjectBehavior
{
    public function let(Observer $observer)
    {
        $this->beAnInstanceOf(ConcreteDenormalizer::class);
        $this->beConstructedWith($observer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Denormalizer::class);
    }

    public function it_throws_an_exception_if_serializer_not_implement_denormalizer_interface(SerializerInterface $otherSerializer)
    {
        $this->shouldThrow(LogicException::class)->during('setSerializer', [$otherSerializer]);
    }


    public function it_is_able_to_create_a_new_object(Observer $observer)
    {
        $data = ['key' => 'value'];
        $context = [
            ObjectNormalizer::OBJECT_TO_POPULATE => null
        ];

        $response = new stdClass();
        $observer->method($data, null)->willReturn($response);

        $this->denormalize($data, '', null, $context)
            ->shouldReturn($response);

        $observer->method($data, null)->shouldBeCalledOnce();
    }

    public function it_is_able_to_update_an_object(Observer $observer)
    {
        $response = (object)['response' => true];
        $entity = (object)['entity' => true];
        $data = ['key' => 'value'];

        $context = [
            ObjectNormalizer::OBJECT_TO_POPULATE => $entity
        ];


        $observer->method($data, $entity)->willReturn($response);
        $this->denormalize($data, '', null, $context)
            ->shouldReturn($response);

        $observer->method($data, $entity)->shouldBeCalledOnce();
    }


    public function it_throws_an_exception_when_mapping_fails(Observer $observer)
    {
        $data = ['key' => 'value'];
        $entity = (object)['entity' => true];

        $context = [
            ObjectNormalizer::OBJECT_TO_POPULATE => $entity
        ];

        $observer->method($data, $entity)->willThrow(new Exception());

        $this->shouldThrow(MappingException::class)->during('denormalize', [
            $data,
            '',
            null,
            $context
        ]);

        $observer->method($data, $entity)->shouldBeCalledOnce();
    }

}

class ConcreteDenormalizer extends Denormalizer
{
    private Observer $observer;

    public function __construct(Observer $observer)
    {
        $this->observer = $observer;
    }

    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return true;
    }

    protected function mapToObject($data, $entity = null): object
    {
        return $this->observer->method($data, $entity);
    }
}

class Observer
{
    public function method($data, $entity)
    {
        if (is_null($data)) {
            throw new \Exception();
        }

        return $data;
    }
}
