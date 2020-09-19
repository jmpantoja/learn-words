<?php

namespace spec\LearnWords\Infrastructure\Domain\Dictionary\Importer\Provider\WordReference;

use LearnWords\Domain\Dictionary\Word;
use LearnWords\Infrastructure\Domain\Dictionary\Importer\Provider\WordReference\WordReferenceHtmlPage;
use org\bovigo\vfs\vfsStream;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class WordReferenceHtmlPageSpec extends ObjectBehavior
{
    public function let(HttpClientInterface $client, ParameterBagInterface $parameterBag)
    {
        $structure = [
            'cache' => [
                'WordReference' => [
                    'ENGLISH' => [
                        'goodbye.html' => 'GOODBYE HTML CONTENT'
                    ]
                ]
            ]
        ];

        vfsStream::setup('root', null, $structure);

        $parameterBag->get('kernel.cache_dir')->willReturn('vfs://root/cache/');

        $this->beConstructedWith($client, $parameterBag);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(WordReferenceHtmlPage::class);
    }

    public function it_is_able_to_download_an_english_word_reference_html_page(HttpClientInterface $client, ResponseInterface $response)
    {
        $word = Word::English('hello');
        $url = 'https://www.wordreference.com/es/translation.asp?tranword=hello';

        $response->getContent()->willReturn('HELLO HTML CONTENT');
        $client->request('GET', $url)->willReturn($response);

        $this->getHtmlByWord($word)->shouldBeLike(new Crawler('HELLO HTML CONTENT'));
    }

    public function it_is_able_to_download_a_spanish_word_reference_html_page(HttpClientInterface $client, ResponseInterface $response)
    {
        $word = Word::Spanish('hola');
        $url = 'https://www.wordreference.com/es/en/translation.asp?spen=hola';

        $response->getContent()->willReturn('HOLA HTML CONTENT');
        $client->request('GET', $url)->willReturn($response);

        $this->getHtmlByWord($word)->shouldBeLike(new Crawler('HOLA HTML CONTENT'));
    }

    public function it_is_able_to_read_a_html_page_from_cache()
    {
        $word = Word::English('goodbye');
        $this->getHtmlByWord($word)->shouldBeLike(new Crawler('GOODBYE HTML CONTENT'));
    }
}
