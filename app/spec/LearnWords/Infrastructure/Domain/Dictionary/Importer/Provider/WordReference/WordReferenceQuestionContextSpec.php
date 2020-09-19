<?php

namespace spec\LearnWords\Infrastructure\Domain\Dictionary\Importer\Provider\WordReference;

use LearnWords\Domain\Dictionary\Example;
use LearnWords\Domain\Dictionary\Wording;
use LearnWords\Infrastructure\Domain\Dictionary\Importer\Provider\WordReference\WordReferenceQuestionContext;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DomCrawler\Crawler;

class WordReferenceQuestionContextSpec extends ObjectBehavior
{
    private Crawler $crawler;
    private $html = <<<eof
<html>
<body>
<table>
<tr class="even" id="enes:884"><td class="FrWrd"><strong>hello,<br> also UK: hallo,<br> hullo</strong> <em class="tooltip POS2">interj<span><i>interjection</i>: Exclamation--for example, "Oh no!"  "Wow!"</span></em></td><td> (greeting)</td><td class="ToWrd">hola <em class="tooltip POS2">interj<span><i>interjección</i>: Interjección o exclamación, propia o impropia, de una sola palabra ("uy", "uf", "bravo", "viva").</span></em></td></tr>
<tr class="even"><td>&nbsp;</td><td colspan="2" class="FrEx">Hello.  How are you?</td></tr>
<tr class="even"><td>&nbsp;</td><td colspan="2" class="ToEx">¡Hola! ¿Cómo estás?</td></tr>
<tr class="odd" id="enes:60692"><td class="FrWrd"><strong>hello</strong> <em class="tooltip POS2">n<span><i>noun</i>: Refers to person, place, thing, quality, etc.</span></em></td><td> <i class="Fr2">informal</i> (spoken greeting)</td><td class="ToWrd">saludos <em class="tooltip POS2">nmpl<span><i>nombre masculino plural</i>: Sustantivo masculino que se usa únicamente en plural, con los artículos <i>los</i> o <i>unos</i>. <i>Ejemplos: los maritates, unos víveres.</i></span></em></td></tr>
<tr class="odd"><td>&nbsp;</td><td colspan="2" class="FrEx">Give Gina a hello from me when you see her!  I\'ll come back and talk to you when I finish my hellos.</td></tr>
<tr class="odd"><td>&nbsp;</td><td colspan="2" class="ToEx">Dale a Gina mis saludos cuando la veas.</td></tr>
<tr class="even" id="enes:3197832"><td class="FrWrd"><strong>hello</strong> <em class="tooltip POS2">interj<span><i>interjection</i>: Exclamation--for example, "Oh no!"  "Wow!"</span></em></td><td> (phone greeting)</td><td class="ToWrd">hola <em class="tooltip POS2">interj<span><i>interjección</i>: Interjección o exclamación, propia o impropia, de una sola palabra ("uy", "uf", "bravo", "viva").</span></em></td></tr>
<tr class="even"><td>&nbsp;</td><td>&nbsp;</td><td class="ToWrd">diga <em class="tooltip POS2">interj<span><i>interjección</i>: Interjección o exclamación, propia o impropia, de una sola palabra ("uy", "uf", "bravo", "viva").</span></em></td></tr>
<tr class="even"><td>&nbsp;</td><td>&nbsp;</td><td class="ToWrd">hable <em class="tooltip POS2">interj<span><i>interjección</i>: Interjección o exclamación, propia o impropia, de una sola palabra ("uy", "uf", "bravo", "viva").</span></em></td></tr>
<tr class="even"><td>&nbsp;</td><td class="To2">&nbsp;<span class="dsense">(<i>AmL</i>)</span></td><td class="ToWrd">aló <em class="tooltip POS2">interj<span><i>interjección</i>: Interjección o exclamación, propia o impropia, de una sola palabra ("uy", "uf", "bravo", "viva").</span></em></td></tr>
<tr class="even"><td>&nbsp;</td><td colspan="2" class="FrEx">Emma swiped her phone to answer the call and said, "Hello?"</td></tr>
<tr class="even"><td>&nbsp;</td><td colspan="2" class="ToEx">Emma deslizó el dedo por el teléfono para atender la llamada y dijo "¿Hola?".</td></tr>
</table>
</body>
</html>
eof;


    public function let()
    {
        $this->crawler = (new Crawler($this->html))->filter('table tr');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(WordReferenceQuestionContext::class);
    }

    public function it_parse_a_question()
    {
        $this->process($this->crawler->eq(0));
        $this->process($this->crawler->eq(1));
        $this->process($this->crawler->eq(2));

        $wording = new Wording('hola', 'greeting');
        $example = new Example('Hello.  How are you?', '¡Hola! ¿Cómo estás?');

        $this->getQuestion()->shouldIterateLike([
            'wording' => $wording,
            'example' => $example
        ]);
    }

    public function it_returns_null_if_question_is_incomplete()
    {
        $this->process($this->crawler->eq(1));
        $this->getQuestion()->shouldReturn(null);
    }


}
