<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Normalizer;

use ArrayIterator;
use IteratorAggregate;
use LearnWords\Infrastructure\Domain\Word\Dto\TagDto;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Entity\Dto;
use PlanB\Edge\Infrastructure\Symfony\Normalizer\Exception\ValidationInputException;
use PlanB\Edge\Infrastructure\Symfony\Normalizer\InputDenormalizer;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InputDenormalizerSpec extends ObjectBehavior
{
    public function let(ValidatorInterface $validator, ConstraintViolationListInterface $violationList)
    {
        $this->beAnInstanceOf(ConcreteInputDenormalizer::class);
        $this->setValidator($validator);

        $violationList->beADoubleOf(IteratorAggregate::class);

        $violationList->count()->willReturn(0);
        $violationList->getIterator()->willReturn(new ArrayIterator([]));
        $validator->validate(Argument::cetera())->willReturn($violationList);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(InputDenormalizer::class);
    }

    public function it_create_a_new_dto_from_an_array(Dto $dto)
    {
        $dto->process(null)->shouldBeCalledOnce();

        $context = [];
        $this->instanceOfFail($dto, $context);
    }

    public function it_update_a_entity_from_an_array(Dto $dto)
    {
        $entity = new stdClass();
        $dto->process($entity)->shouldBeCalledOnce();

        $context = [ObjectNormalizer::OBJECT_TO_POPULATE => $entity];
        $this->instanceOfFail($dto, $context);
    }

    public function it_throws_an_exception_when_validation_fails(Dto $dto, ConstraintViolationListInterface $violationList)
    {
        $entity = new stdClass();
        $violationList->count()->willReturn(1);
        $dto->process($entity)->shouldNotBeCalled();

        $context = [ObjectNormalizer::OBJECT_TO_POPULATE => $entity];
        $this->shouldThrow(ValidationInputException::class)->during('instanceOfFail', [$dto, $context]);
    }
}

class ConcreteInputDenormalizer extends InputDenormalizer
{

    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        $dto = TagDto::fromArray($data);
        return $this->instanceOfFail($dto, $context);
    }
}

class DummyDto extends Dto
{

    public function update($entity): object
    {
        // TODO: Implement update() method.
    }

    public function create(): object
    {
        // TODO: Implement create() method.
    }
}
