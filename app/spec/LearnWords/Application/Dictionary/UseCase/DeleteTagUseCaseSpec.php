<?php

namespace spec\LearnWords\Application\Dictionary\UseCase;

use LearnWords\Application\Dictionary\UseCase\DeleteTag;
use LearnWords\Application\Dictionary\UseCase\DeleteTagUseCase;
use LearnWords\Domain\Dictionary\Tag;
use LearnWords\Domain\Dictionary\TagRepository;
use PhpSpec\ObjectBehavior;

class DeleteTagUseCaseSpec extends ObjectBehavior
{
    public function let(TagRepository $tagRepository)
    {
        $this->beConstructedWith($tagRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DeleteTagUseCase::class);
    }

    public function it_is_able_to_delete_a_tag(Tag $tag,
                                               TagRepository $tagRepository)
    {
        $command = new DeleteTag($tag->getWrappedObject());
        $this->handle($command);

        $tagRepository->delete($tag)->shouldBeCalled();
    }
}
