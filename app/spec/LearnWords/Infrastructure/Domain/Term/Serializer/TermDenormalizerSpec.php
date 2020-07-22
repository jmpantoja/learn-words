<?php

namespace spec\LearnWords\Infrastructure\Domain\Term\Serializer;

use LearnWords\Domain\Tag\Tag;
use LearnWords\Domain\Tag\TagList;
use LearnWords\Domain\Term\Term;
use LearnWords\Domain\Term\Word;
use LearnWords\Infrastructure\Domain\Term\Serializer\TermDenormalizer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Serializer\Exception\MappingException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class TermDenormalizerSpec extends ObjectBehavior
{
    public function let(DenormalizerInterface $serializer)
    {
        $serializer->beADoubleOf(SerializerInterface::class);
        $this->setSerializer($serializer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TermDenormalizer::class);
    }

    public function it_detects_if_a_value_is_supported()
    {
        $this->supportsDenormalization(Argument::any(), Term::class)
            ->shouldReturn(true);
    }

    public function it_detects_if_a_value_is_not_supported()
    {
        $this->supportsDenormalization(Argument::any(), 'otra-cosa')
            ->shouldReturn(false);
    }

    public function it_is_able_to_create_a_term_from_an_array(DenormalizerInterface $serializer)
    {
        $word = Word::spanish('word');

        $tagList = TagList::collect([
            new Tag('tag-A'),
            new Tag('tag-B'),
        ]);

        $serializer->denormalize(Argument::any(), Word::class)
            ->willReturn($word);

        $input = [
            'word' => 'hola',
            'tags' => $tagList
        ];

        $response = $this->denormalize($input, Term::class);
        $response->shouldBeAnInstanceOf(Term::class);

        $response->getWord()->shouldReturn($word);
        $response->getTags()->shouldBeLike($tagList);
    }

    public function it_is_able_to_update_a_term_from_an_array(DenormalizerInterface $serializer)
    {
        $word = Word::spanish('word');

        $tagList = TagList::collect([
            new Tag('tag-A'),
            new Tag('tag-B'),
        ]);

        $serializer->denormalize(Argument::any(), Word::class)
            ->willReturn($word);

        $input = [
            'word' => 'hola',
            'tags' => $tagList
        ];


        $term = new Term(Word::spanish('valor'), TagList::empty());

        $response = $this->denormalize($input, Term::class, null, [
            ObjectNormalizer::OBJECT_TO_POPULATE => $term
        ]);

        $response->shouldReturn($term);

        $response->getWord()->shouldReturn($word);
        $response->getTags()->shouldBeLike($tagList);
    }


    public function it_throws_an_exception_when_input_array_is_invalid()
    {

        $input = [
            'XXX' => 'hola',
            'YYY' => 'SPANISH'
        ];

        $this->shouldThrow(MappingException::class)->during('denormalize', [$input, Term::class]);
    }
}
