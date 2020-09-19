<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace LearnWords\Infrastructure\Domain\Dictionary\Importer\Provider\WordReference;


use Cocur\Slugify\Slugify;
use LearnWords\Domain\Dictionary\Word;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WordReferenceHtmlPage
{
    private HttpClientInterface $client;
    private string $cacheDir;


    public function __construct(HttpClientInterface $client, ParameterBagInterface $parameterBag)
    {
        $this->client = $client;
        $this->cacheDir = sprintf('%s/WordReference', $parameterBag->get('kernel.cache_dir'));
    }

    public function getHtmlByWord(Word $word): Crawler
    {
        $html = $this->fromCache($word, function (Word $word) {
            $url = $this->parseUrl($word);
            $response = $this->client->request('GET', $url);
            return $response->getContent();
        });

        return new Crawler($html);
    }

    private function fromCache(Word $word, callable $callback): string
    {

        $slugger = Slugify::create();

        $fileSystem = new Filesystem();
        $key = sprintf('%s/%s', $word->getLang(), $slugger->slugify($word->getWord()));
        $path = sprintf('%s/%s.html', $this->cacheDir, $key);


        if ($fileSystem->exists($path)) {
            return file_get_contents($path);
        }

        $html = $callback($word);
        $fileSystem->dumpFile($path, $html);

        return $html;

    }

    /**
     * @param Word $word
     * @return string
     */
    protected function parseUrl(Word $word): string
    {
        if($word->isEnglish()){
            return sprintf('https://www.wordreference.com/es/translation.asp?tranword=%s', $word);
        }

        return sprintf('https://www.wordreference.com/es/en/translation.asp?spen=hola', $word);
    }
}
