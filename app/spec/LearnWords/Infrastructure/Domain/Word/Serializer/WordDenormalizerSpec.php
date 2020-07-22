<?php

namespace spec\LearnWords\Infrastructure\Domain\Word\Serializer;

use LearnWords\Domain\Word\Lang;
use LearnWords\Domain\Word\Tag;
use LearnWords\Domain\Word\TagList;
use LearnWords\Domain\Word\Word;
use LearnWords\Infrastructure\Domain\Word\Serializer\WordDenormalizer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Serializer\Exception\MappingException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class WordDenormalizerSpec extends ObjectBehavior
{
    public function let(DenormalizerInterface $serializer)
    {
        $serializer->beADoubleOf(SerializerInterface::class);
        $this->setSerializer($serializer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(WordDenormalizer::class);
    }

    public function it_detects_if_a_value_is_supported()
    {
        $this->supportsDenormalization(Argument::any(), Word::class)
            ->shouldReturn(true);
    }

    public function it_detects_if_a_value_is_not_supported()
    {
        $this->supportsDenormalization(Argument::any(), 'otra-cosa')
            ->shouldReturn(false);
    }

    public function it_is_able_to_create_a_word_from_an_array(DenormalizerInterface $serializer)
    {
        $serializer->denormalize('SPANISH', Lang::class)->willReturn(Lang::SPANISH());

        $tagList = TagList::collect([
            new Tag('tag-A'),
            new Tag('tag-B'),
        ]);


        $input = [
            'word' => 'hola',
            'lang' => 'SPANISH',
            'tags' => $tagList
        ];

        $response = $this->denormalize($input, Word::class);
        $response->shouldBeAnInstanceOf(Word::class);

        $response->getWord()->shouldReturn('hola');
        $response->getLang()->shouldBeLike(Lang::SPANISH());
        $response->getTags()->shouldBeLike($tagList);
    }

    public function it_is_able_to_update_a_word_from_an_array(DenormalizerInterface $serializer)
    {

        $serializer->denormalize('SPANISH', Lang::class)->willReturn(Lang::SPANISH());

        $tagList = TagList::collect([
            new Tag('tag-A'),
            new Tag('tag-B'),
        ]);

        $input = [
            'word' => 'hola',
            'lang' => 'SPANISH',
            'tags' => $tagList
        ];


        $word = new Word('valor', Lang::SPANISH(), TagList::empty());

        $response = $this->denormalize($input, Word::class, null, [
            ObjectNormalizer::OBJECT_TO_POPULATE => $word
        ]);

        $response->shouldReturn($word);

        $response->getWord()->shouldReturn('hola');
        $response->getLang()->shouldBeLike(Lang::SPANISH());
        $response->getTags()->shouldBeLike($tagList);
    }


    public function it_throws_an_exception_when_input_array_is_invalid()
    {

        $input = [
            'XXX' => 'hola',
            'YYY' => 'SPANISH'
        ];

        $this->shouldThrow(MappingException::class)->during('denormalize', [$input, Word::class]);
    }
}
