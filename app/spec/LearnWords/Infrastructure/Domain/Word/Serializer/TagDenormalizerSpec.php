<?php

namespace spec\LearnWords\Infrastructure\Domain\Word\Serializer;

use LearnWords\Domain\Word\Lang;
use LearnWords\Domain\Word\Tag;
use LearnWords\Domain\Word\TagList;
use LearnWords\Domain\Word\TagRepository;
use LearnWords\Domain\Word\Word;
use LearnWords\Infrastructure\Domain\Word\Serializer\TagDenormalizer;
use LearnWords\Infrastructure\Domain\Word\Serializer\WordDenormalizer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Serializer\Exception\MappingException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class TagDenormalizerSpec extends ObjectBehavior
{
    public function let(DenormalizerInterface $serializer, TagRepository $repository)
    {
        $this->beConstructedWith($repository);

        $serializer->beADoubleOf(SerializerInterface::class);
        $this->setSerializer($serializer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TagDenormalizer::class);
    }

    public function it_detects_if_a_value_is_supported()
    {
        $this->supportsDenormalization([], Tag::class)
            ->shouldReturn(true);
    }

    public function it_detects_if_a_value_is_not_supported()
    {
        $this->supportsDenormalization(Argument::any(), 'otra-cosa')
            ->shouldReturn(false);
    }

    public function it_is_able_to_create_a_word_from_an_array(DenormalizerInterface $serializer)
    {
        $input = ['tag' => 'hola'];

        $response = $this->denormalize($input, Tag::class);
        $response->shouldBeAnInstanceOf(Tag::class);
        $response->getTag()->shouldReturn('hola');
    }

    public function it_is_able_to_update_a_word_from_an_array(DenormalizerInterface $serializer)
    {
        $input = ['tag' => 'hola'];
        $tag = new Tag('valor');

        $response = $this->denormalize($input, Tag::class, null, [
            ObjectNormalizer::OBJECT_TO_POPULATE => $tag
        ]);

        $response->shouldReturn($tag);
        $response->getTag()->shouldReturn('hola');
    }

    public function it_throws_an_exception_when_input_array_is_invalid()
    {
        $input = [
            'XXX' => 'hola'
        ];

        $this->shouldThrow(MappingException::class)->during('denormalize', [$input, Tag::class]);
    }
}
