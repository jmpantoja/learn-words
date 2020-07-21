<?php

namespace spec\LearnWords\Infrastructure\Domain\Term\Serializer;

use LearnWords\Domain\Term\Lang;
use LearnWords\Domain\Term\Word;
use LearnWords\Infrastructure\Domain\Term\Serializer\WordDenormalizer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Serializer\Exception\MappingException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
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

    public function it_is_able_to_map_a_object_type_word()
    {

        $input = Word::spanish('hola');

        $this->denormalize($input, Word::class)
            ->shouldReturn($input);
    }

    public function it_is_able_to_map_an_array(DenormalizerInterface $serializer)
    {
        $serializer->denormalize('SPANISH', Lang::class)
            ->willReturn(Lang::SPANISH());

        $response = Word::spanish('hola');

        $input = [
            'word' => 'hola',
            'lang' => 'SPANISH'
        ];

        $this->denormalize($input, Word::class)
            ->shouldBeLike($response);
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
