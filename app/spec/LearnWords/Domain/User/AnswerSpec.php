<?php

namespace spec\LearnWords\Domain\User;

use Carbon\CarbonImmutable;
use LearnWords\Domain\Dictionary\Question;
use LearnWords\Domain\User\Answer;
use LearnWords\Domain\User\AnswerStatus;
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
        $this->getLeitner()->shouldBeLike(Leitner::INITIAL());
    }

    public function it_has_right_status_after_be_wrong()
    {
        $this->resolve(AnswerStatus::WRONG());

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

    public function it_has_right_status_after_be_wrong_twice()
    {
        $this->resolve(AnswerStatus::WRONG());
        $this->resolve(AnswerStatus::WRONG());

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

    public function it_has_right_status_after_be_right_first_time()
    {
        $this->resolve(AnswerStatus::RIGHT());

        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today()->addDays(1);
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike($today);
        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(1);
        $this->getTotalFailures()->shouldReturn(0);

        $this->getLeitner()->shouldBeLike(Leitner::EACH_DAY());
    }

    public function it_has_right_status_after_be_right_second_time()
    {
        $this->resolve(AnswerStatus::RIGHT());
        $this->resolve(AnswerStatus::RIGHT());

        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today()->addDays(3);
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike($today);
        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(2);
        $this->getTotalFailures()->shouldReturn(0);

        $this->getLeitner()->shouldBeLike(Leitner::EACH_THREE_DAYS());
    }

    public function it_has_right_status_after_be_right_third_time()
    {
        $this->resolve(AnswerStatus::RIGHT());
        $this->resolve(AnswerStatus::RIGHT());
        $this->resolve(AnswerStatus::RIGHT());

        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today()->addDays(7);
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike($today);
        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(3);
        $this->getTotalFailures()->shouldReturn(0);

        $this->getLeitner()->shouldBeLike(Leitner::EACH_WEEK());
    }

    public function it_has_right_status_after_be_right_fourth_time()
    {
        $this->resolve(AnswerStatus::RIGHT());
        $this->resolve(AnswerStatus::RIGHT());
        $this->resolve(AnswerStatus::RIGHT());
        $this->resolve(AnswerStatus::RIGHT());

        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today()->addDays(14);
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike($today);
        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(4);
        $this->getTotalFailures()->shouldReturn(0);

        $this->getLeitner()->shouldBeLike(Leitner::EACH_TWO_WEEKS());
    }

    public function it_has_right_status_after_be_right_fith_or_more_times()
    {
        $this->resolve(AnswerStatus::RIGHT());
        $this->resolve(AnswerStatus::RIGHT());
        $this->resolve(AnswerStatus::RIGHT());
        $this->resolve(AnswerStatus::RIGHT());
        $this->resolve(AnswerStatus::RIGHT());

        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today()->addDays(30);
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike($today);
        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(5);
        $this->getTotalFailures()->shouldReturn(0);

        $this->getLeitner()->shouldBeLike(Leitner::EACH_MONTH());
    }


    public function it_has_right_status_when_wrong()
    {
        $this->resolve(AnswerStatus::RIGHT());
        $this->resolve(AnswerStatus::RIGHT());
        $this->resolve(AnswerStatus::WRONG());

        $today = CarbonImmutable::today();
        $nextDate = CarbonImmutable::today();
        $next = $nextDate->format('Ymd') * 1;

        $this->getLastDate()->shouldBeLike($today);
//        $this->getNextDate()->shouldBeLike($nextDate);
        $this->getNext()->shouldBe($next);

        $this->getTotalSuccess()->shouldReturn(2);
        $this->getTotalFailures()->shouldReturn(1);

        $this->getLeitner()->shouldBeLike(Leitner::INITIAL());
    }


}
