<?php

namespace spec\LearnWords\Domain\Dictionary\Importer;

use ArrayIterator;
use Generator;
use LearnWords\Domain\Dictionary\EntryRepository;
use LearnWords\Domain\Dictionary\Example;
use LearnWords\Domain\Dictionary\Importer\ImportResolver;
use LearnWords\Domain\Dictionary\Importer\Provider\QuestionProviderInterface;
use LearnWords\Domain\Dictionary\Importer\Reader\ReaderInterface;
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

    public function let(QuestionProviderInterface $questionProvider, EntryRepository $entryRepository, TagRepository $tagRepository)
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

        $this->beConstructedWith($questionProvider, $entryRepository, $tagRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ImportResolver::class);
    }

    public function it_is_able_to_create_an_entry_from_a_reader(ReaderInterface $reader, TagRepository $tagRepository)
    {
        $reader->getIterator()->willReturn(new ArrayIterator([
            [
                'word' => 'hello',
                'notes' => 'una forma de saludar',
                'tags' => ['saludos', 'a1']
            ]
        ]));

        $entries = $this->resolve($reader, Lang::ENGLISH());

        $entries->shouldBeAnInstanceOf(Generator::class);

        $entries->current()->getWord()->shouldBeLike(Word::English('hello'));

        $entries->current()->getTags()->get(0)->getTag()->shouldReturn('saludos');
        $entries->current()->getTags()->get(1)->getTag()->shouldReturn('a1');

        $entries->current()->getQuestions()->get(0)->getWording()->getWording()->shouldReturn('hola');

        $tagRepository->createTagList('saludos', 'a1')->shouldHaveBeenCalled();

    }
}
