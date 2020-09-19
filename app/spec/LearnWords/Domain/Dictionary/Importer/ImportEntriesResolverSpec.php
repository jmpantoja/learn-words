<?php

namespace spec\LearnWords\Domain\Dictionary\Importer;

use ArrayIterator;
use LearnWords\Domain\Dictionary\Example;
use LearnWords\Domain\Dictionary\Importer\ImportEntriesResolver;
use LearnWords\Domain\Dictionary\Importer\Provider\QuestionProviderInterface;
use LearnWords\Domain\Dictionary\Importer\Reader\EntriesReaderInterface;
use LearnWords\Domain\Dictionary\Lang;
use LearnWords\Domain\Dictionary\Tag;
use LearnWords\Domain\Dictionary\TagList;
use LearnWords\Domain\Dictionary\TagRepository;
use LearnWords\Domain\Dictionary\Word;
use LearnWords\Domain\Dictionary\Wording;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ImportEntriesResolverSpec extends ObjectBehavior
{

    public function let(QuestionProviderInterface $questionProvider, TagRepository $tagRepository)
    {

        $questionProvider->byWord(Argument::any())->willReturn([
            [
                'wording' => new Wording('hola', 'greeting'),
                'example' => new Example('Hello. How are you?', '¡Hola! ¿Cómo estás?'),
            ]
        ]);

        $tagList = TagList::collect([
            new Tag('saludos'),
            new Tag('a1'),
        ]);

        $tagRepository->createTagList('saludos', 'a1')->willReturn($tagList);

        $this->beConstructedWith($questionProvider, $tagRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ImportEntriesResolver::class);
    }

    public function it_is_able_to_create_an_entry_from_a_reader(EntriesReaderInterface $reader, TagRepository $tagRepository)
    {
        $tagList = TagList::collect([
            new Tag('saludos'),
            new Tag('a1'),
        ]);

        $reader->getIterator()->willReturn(new ArrayIterator([
            [
                'word' => 'hello',
                'notes' => 'una forma de saludar',
                'tags' => ['saludos', 'a1']
            ]
        ]));

        $entries = $this->resolve($reader, Lang::ENGLISH());

        $entries[0]->getWord()->shouldBeLike(Word::English('hello'));

        $entries[0]->getTags()->get(0)->getTag()->shouldReturn('saludos');
        $entries[0]->getTags()->get(1)->getTag()->shouldReturn('a1');

        $entries[0]->getQuestions()->get(0)->getWording()->getWording()->shouldReturn('hola');

        $tagRepository->createTagList('saludos', 'a1')->shouldHaveBeenCalled();

    }
}
