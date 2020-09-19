<?php

namespace spec\LearnWords\Infrastructure\Domain\Dictionary\Transformer;

use LearnWords\Domain\Dictionary\Tag;
use LearnWords\Infrastructure\Domain\Dictionary\Dto\TagDto;
use LearnWords\Infrastructure\Domain\Dictionary\Transformer\TagNormalizer;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Transformer\Transformer;
use Prophecy\Argument;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TagNormalizerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TagNormalizer::class);
        $this->shouldHaveType(Transformer::class);
    }

    public function it_supports_denormalization_if_type_is_tag()
    {
        $this->supportsDenormalization(Argument::any(), Tag::class, Argument::cetera())
            ->shouldReturn(true);
    }

    public function it_does_not_supports_denormalization_if_type_is_not_tag()
    {
        $this->supportsDenormalization(Argument::any(), Argument::cetera())
            ->shouldReturn(false);
    }

    public function it_is_able_to_create_a_new_tag()
    {
        $data = TagDto::fromArray([
            'tag' => 'name'
        ]);

        $tag = $this->denormalize($data, Tag::class, null, [
            ObjectNormalizer::OBJECT_TO_POPULATE => null
        ]);

        $tag->shouldBeAnInstanceOf(Tag::class);
        $tag->getTag()->shouldReturn('name');
    }


    public function it_is_able_to_update_an_existent_tag()
    {
        $previous = new Tag('old value');

        $data = TagDto::fromArray([
            'tag' => 'name'
        ]);

        $tag = $this->denormalize($data, Tag::class, null, [
            ObjectNormalizer::OBJECT_TO_POPULATE => $previous
        ]);

        $tag->shouldReturn($previous);
        $tag->getTag()->shouldReturn('name');
    }
}
