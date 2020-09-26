<?php

namespace spec\LearnWords\Domain\Dictionary;

use LearnWords\Domain\Dictionary\Mp3Url;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Validator\Exception\ValidationException;

class Mp3UrlSpec extends ObjectBehavior
{
    private const URL = 'https://www.wordreference.com/audio/en/us/us/en004244.mp3';
    private const BAD_URL = 'www.wordreference.com/audio/en/us/us/en004244.mp3';
    private const BAD_EXT = 'https://www.wordreference.com/audio/en/us/us/en004244.jpg';

    public function let()
    {
        $this->beConstructedWith(self::URL);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Mp3Url::class);
        $this->getUrl()->shouldReturn(self::URL);
    }

    public function it_throws_an_exception_when_input_data_is_not_an_url()
    {
        $this->beConstructedWith(self::BAD_URL);
        $this->shouldThrow(ValidationException::class)->duringInstantiation();
    }

    public function it_throws_an_exception_when_input_data_doesnt_have_mp3_extension()
    {
        $this->beConstructedWith(self::BAD_EXT);
        $this->shouldThrow(ValidationException::class)->duringInstantiation();
    }
}
