<?php

namespace spec\LearnWords\Application\Word\UseCase;

use LearnWords\Application\Word\UseCase\DeleteTag;
use LearnWords\Application\Word\UseCase\DeleteTagUseCase;
use LearnWords\Domain\Word\Tag;
use LearnWords\Domain\Word\TagRepository;
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
