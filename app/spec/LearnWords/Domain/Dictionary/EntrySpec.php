<?php

namespace spec\LearnWords\Domain\Dictionary;

use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\EntryId;
use LearnWords\Domain\Dictionary\Example;
use LearnWords\Domain\Dictionary\Lang;
use LearnWords\Domain\Dictionary\Question;
use LearnWords\Domain\Dictionary\Relevance;
use LearnWords\Domain\Dictionary\Tag;
use LearnWords\Domain\Dictionary\TagList;
use LearnWords\Domain\Dictionary\Word;
use LearnWords\Domain\Dictionary\Wording;
use PhpSpec\ObjectBehavior;

class EntrySpec extends ObjectBehavior
{
    public function let()
    {
        $word = Word::English('hello');
        $tagList = TagList::collect([
            new Tag('tagA'),
            new Tag('tagB'),
        ]);

        $this->beConstructedWith($word, $tagList);
    }

    public function it_is_initializable()
    {
        $word = Word::English('hello');

        $this->shouldHaveType(Entry::class);

        $this->getId()->shouldBeAnInstanceOf(EntryId::class);
        $this->getWord()->shouldBeLike($word);

        $this->getTags()->get(0)->getTag()->shouldReturn('tagA');
        $this->getTags()->get(1)->getTag()->shouldReturn('tagB');

    }

    public function it_is_updatable()
    {
        $word = Word::English('hello');
        $tagList = TagList::collect([
            new Tag('tagC'),
            new Tag('tagD'),
        ]);

        $this->update($word, $tagList);

        $this->getId()->shouldBeAnInstanceOf(EntryId::class);
        $this->getWord()->shouldBeLike($word);

        $this->getTags()->get(0)->getTag()->shouldReturn('tagC');
        $this->getTags()->get(1)->getTag()->shouldReturn('tagD');
    }

    public function it_is_able_to_add_questions()
    {
        $this->getQuestions()->isEmpty()->shouldReturn(true);

        $wording = new Wording('wording', 'description');
        $example = new Example('sample', 'translation');
        $this->addQuestion([
            'wording' => $wording,
            'example' => $example,
        ]);

        $this->getQuestions()->count()->shouldReturn(1);
        $this->getQuestions()->get(0)->beAnInstanceOf(Question::class);
        $this->getQuestions()->get(0)->getWording()->shouldReturn($wording);
        $this->getQuestions()->get(0)->getExample()->shouldReturn($example);
        $this->getQuestions()->get(0)->getRelevance()->shouldBeLike(new Relevance(1));
    }

    public function it_is_able_to_update_questions()
    {
        $wording = new Wording('wording', 'description');
        $example = new Example('sample', 'translation');
        $this->addQuestion([
            'wording' => $wording,
            'example' => $example,
        ]);

        $newWording = new Wording('wording', 'description');
        $newExample = new Example('sample', 'translation');
        $newRelevance = new Relevance(4);

        $this->updateQuestion(0, [
            'wording' => $newWording,
            'example' => $newExample,
            'relevance' => $newRelevance,
        ]);

        $this->getQuestions()->get(0)->getWording()->shouldReturn($newWording);
        $this->getQuestions()->get(0)->getExample()->shouldReturn($newExample);
        $this->getQuestions()->get(0)->getRelevance()->shouldReturn($newRelevance);
    }

    public function it_is_able_to_delete_questions()
    {
        $wording = new Wording('wording', 'description');
        $example = new Example('sample', 'translation');
        $this->addQuestion([
            'wording' => $wording,
            'example' => $example,
        ]);

        $this->getQuestions()->count()->shouldReturn(1);

        $question = $this->getQuestions()->get(0);
        $this->removeQuestion($question);

        $this->getQuestions()->count()->shouldReturn(0);
    }


}
