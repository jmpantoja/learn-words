<?php

namespace spec\PlanB\Edge\Domain\Transformer;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Dto\Dto;
use PlanB\Edge\Domain\Transformer\Transformer;
use stdClass;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TransformerSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ConcreteTransformer::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Transformer::class);
    }

    public function it_is_able_to_create_an_object(Dto $dto)
    {
        $response = $this->denormalize($dto, 'type', null, []);
        $response->created->shouldReturn(true);
    }

    public function it_is_able_to_update_an_object(Dto $dto)
    {
        $subject = new stdClass();

        $response = $this->denormalize($dto, 'type', null, [
            ObjectNormalizer::OBJECT_TO_POPULATE => $subject
        ]);

        $response->updated->shouldReturn(true);
    }
}

class ConcreteTransformer extends Transformer
{

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return true;
    }

    public function create(Dto $data): object
    {
        $subject = new stdClass();
        $subject->created = true;

        return $subject;
    }

    public function update(Dto $data, $object): object
    {
        $subject = new stdClass();
        $subject->updated = true;

        return $subject;
    }
}
