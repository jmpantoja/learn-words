<?php

namespace spec\LearnWords\Application\Word\UseCase;

use LearnWords\Application\Word\UseCase\SaveTag;
use LearnWords\Application\Word\UseCase\SaveTagUseCase;
use LearnWords\Domain\Word\Tag;
use LearnWords\Domain\Word\TagPersistence\TagHasBeenCreated;
use LearnWords\Domain\Word\TagRepository;
use LearnWords\Domain\Word\WordPersistence\WordHasBeenCreated;
use LearnWords\Domain\Word\WordRepository;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use PlanB\Edge\Domain\Event\DomainEventsCollector;
use Prophecy\Argument;

class SaveTagUseCaseSpec extends ObjectBehavior
{
    public function let(TagRepository $tagRepository, DomainEventsCollector $eventsCollector)
    {
        $eventsCollector->handle(Argument::any(), Argument::any())->willReturn($eventsCollector);

        $this->beConstructedWith($tagRepository);

        DomainEventDispatcher::getInstance()
            ->setDomainEventsCollector($eventsCollector->getWrappedObject());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SaveTagUseCase::class);
    }

    public function it_is_able_to_save_a_tag(TagRepository $tagRepository,
                                              DomainEventsCollector $eventsCollector)
    {
        $tag = new Tag('hola');

        $command = new SaveTag($tag);
        $this->handle($command);
        $tagRepository->persist($tag)->shouldHaveBeenCalledOnce();

        $eventsCollector->handle(Argument::type(TagHasBeenCreated::class), TagHasBeenCreated::class)
            ->shouldHaveBeenCalledOnce();
    }
}

