<?php

namespace spec\LearnWords\Infrastructure\Domain\Dictionary\Transformer;

use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\Example;
use LearnWords\Domain\Dictionary\Lang;
use LearnWords\Domain\Dictionary\Tag;
use LearnWords\Domain\Dictionary\TagList;
use LearnWords\Domain\Dictionary\Word;
use LearnWords\Domain\Dictionary\Wording;
use LearnWords\Infrastructure\Domain\Dictionary\Dto\EntryDto;
use LearnWords\Infrastructure\Domain\Dictionary\Transformer\EntryNormalizer;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Collection\SnapshotList;
use PlanB\Edge\Domain\Transformer\Transformer;
use Prophecy\Argument;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EntryNormalizerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(EntryNormalizer::class);
        $this->shouldHaveType(Transformer::class);
    }

    public function it_supports_denormalization_if_type_is_tag()
    {
        $this->supportsDenormalization(Argument::any(), Entry::class, Argument::cetera())
            ->shouldReturn(true);
    }

    public function it_does_not_supports_denormalization_if_type_is_not_entry()
    {
        $this->supportsDenormalization(Argument::any(), Argument::cetera())
            ->shouldReturn(false);
    }

    public function it_is_able_to_create_a_new_entry()
    {
        $data = $this->getDto();

        $entry = $this->denormalize($data, Tag::class, null, [
            ObjectNormalizer::OBJECT_TO_POPULATE => null
        ]);

        $entry->shouldBeAnInstanceOf(Entry::class);
        $entry->getWord()->getWord()->shouldReturn('hello');

        $entry->getQuestions()->count()->shouldReturn(2);
    }


    public function it_is_able_to_update_an_existent_entry()
    {
        $word = Word::English('old value');
        $tagList = TagList::empty();

        $previous = new Entry($word, $tagList);
        $data = $this->getDto();

        $tag = $this->denormalize($data, Tag::class, null, [
            ObjectNormalizer::OBJECT_TO_POPULATE => $previous
        ]);

        $tag->shouldReturn($previous);
        $tag->getWord()->getWord()->shouldReturn('hello');

        $tag->getQuestions()->count()->shouldReturn(2);
    }


    /**
     * @return EntryDto
     */
    private function getDto(): EntryDto
    {
        $word = Word::English('hello');
        $tagList = TagList::empty();

        $data = EntryDto::fromArray([
            'word' => $word,
            'tags' => $tagList,
            'questions' => SnapshotList::init([[
                'wording' => new Wording('question 1', 'description 1'),
                'example' => new Example('sample 1', 'translation 1')
            ], [
                'wording' => new Wording('question 2', 'description 2'),
                'example' => new Example('sample 2', 'translation 2')
            ],
            ])
        ]);
        return $data;
    }
}
