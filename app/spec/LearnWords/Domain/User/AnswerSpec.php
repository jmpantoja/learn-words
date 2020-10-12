<?php

namespace spec\LearnWords\Domain\User;

use Carbon\CarbonImmutable;
use LearnWords\Domain\Dictionary\Question;
use LearnWords\Domain\User\Answer;
use LearnWords\Domain\User\AnswerStatus;
use LearnWords\Domain\User\GivenText;
use LearnWords\Domain\User\Leitner;
use LearnWords\Domain\User\User;
use PhpSpec\ObjectBehavior;

class AnswerSpec extends ObjectBehavior
{
    public function let(User $user, Question $question)
    {
        $this->beConstructedWith($user, $question);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Answer::class);
    }

    public function it_has_right_status_when_is_initialized(User $user, Question $question)
    {
        $this->getUser()->shouldReturn($user);
        $this->getQuestion()->shouldReturn($question);
        $this->getLastDate()->shouldReturn(null);
        $this->getNextDate()->shouldBeLike(CarbonImmutable::today());
        $this->getNext()->shouldBe(CarbonImmutable::today()->format('Ymd') * 1);

        $this->getTotalSuccess()->shouldReturn(0);
        $this->getTotalFailures()->shouldReturn(0);
        $this->getLeitner()->shouldBeLike(Leitner::TODAY());
    }

    public function it_has_right_status_after_be_wrong(Question $question, GivenText $response)
    {
        $response->isEmpty()->willReturn(false);
        $question->match($response)->willReturn(false);

        $this->resolve($response);

        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today();
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike($today);
        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(0);
        $this->getTotalFailures()->shouldReturn(1);

        $this->getLeitner()->shouldBeLike(Leitner::INITIAL());
    }

    public function it_has_right_status_after_be_wrong_twice(Question $question, GivenText $response)
    {
        $response->isEmpty()->willReturn(false);
        $question->match($response)->willReturn(false);

        $this->resolve($response);
        $this->resolve($response);

        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today();
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike($today);
        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(0);
        $this->getTotalFailures()->shouldReturn(2);

        $this->getLeitner()->shouldBeLike(Leitner::INITIAL());
    }

    public function it_has_right_status_after_be_right_first_time(Question $question, GivenText $response)
    {
        $response->isEmpty()->willReturn(false);
        $question->match($response)->willReturn(true);

        $this->resolve($response);

        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today()->addDays(1);
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike($today);
        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(1);
        $this->getTotalFailures()->shouldReturn(0);

        $this->getLeitner()->shouldBeLike(Leitner::INITIAL());
    }

    public function it_has_right_status_after_be_right_second_time(Question $question, GivenText $response)
    {
        $response->isEmpty()->willReturn(false);
        $question->match($response)->willReturn(true);

        $this->resolve($response);
        $this->resolve($response);

        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today()->addDays(1);
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike($today);
        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(2);
        $this->getTotalFailures()->shouldReturn(0);

        $this->getLeitner()->shouldBeLike(Leitner::EACH_DAY());
    }

    public function it_has_right_status_after_be_right_third_time(Question $question, GivenText $response)
    {
        $response->isEmpty()->willReturn(false);
        $question->match($response)->willReturn(true);

        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);


        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today()->addDays(3);
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike($today);
        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(3);
        $this->getTotalFailures()->shouldReturn(0);

        $this->getLeitner()->shouldBeLike(Leitner::EACH_THREE_DAYS());
    }

    public function it_has_right_status_after_be_right_fourth_time(Question $question, GivenText $response)
    {
        $response->isEmpty()->willReturn(false);
        $question->match($response)->willReturn(true);

        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);

        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today()->addDays(7);
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike($today);
        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(4);
        $this->getTotalFailures()->shouldReturn(0);

        $this->getLeitner()->shouldBeLike(Leitner::EACH_WEEK());
    }

    public function it_has_right_status_after_be_right_fith_time(Question $question, GivenText $response)
    {
        $response->isEmpty()->willReturn(false);
        $question->match($response)->willReturn(true);

        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);

        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today()->addDays(14);
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike($today);
        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(5);
        $this->getTotalFailures()->shouldReturn(0);

        $this->getLeitner()->shouldBeLike(Leitner::EACH_TWO_WEEKS());
    }

    public function it_has_right_status_after_be_right_sixth_times(Question $question, GivenText $response)
    {
        $response->isEmpty()->willReturn(false);
        $question->match($response)->willReturn(true);

        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);

        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today()->addDays(30);
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike($today);
        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(6);
        $this->getTotalFailures()->shouldReturn(0);

        $this->getLeitner()->shouldBeLike(Leitner::EACH_MONTH());
    }

    public function it_has_right_status_after_be_right_more_than_six_times(Question $question, GivenText $response)
    {
        $response->isEmpty()->willReturn(false);
        $question->match($response)->willReturn(true);

        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);

        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today()->addDays(30);
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike($today);
        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(8);
        $this->getTotalFailures()->shouldReturn(0);

        $this->getLeitner()->shouldBeLike(Leitner::EACH_MONTH());
    }


    public function it_has_right_status_when_wrong(Question $question, GivenText $response)
    {
        $response->isEmpty()->willReturn(false);
        $question->match($response)->willReturn(true, true, false);

        $this->resolve($response);
        $this->resolve($response);
        $this->resolve($response);


        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today();
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike($today);
        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(2);
        $this->getTotalFailures()->shouldReturn(1);

        $this->getLeitner()->shouldBeLike(Leitner::INITIAL());
    }

    public function it_has_right_status_when_is_empty(Question $question, GivenText $response)
    {
        $response->isEmpty()->willReturn(true);

        $this->resolve($response);

        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today();
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike(null);
        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(0);
        $this->getTotalFailures()->shouldReturn(0);

        $this->getLeitner()->shouldBeLike(Leitner::TODAY());
    }

}
