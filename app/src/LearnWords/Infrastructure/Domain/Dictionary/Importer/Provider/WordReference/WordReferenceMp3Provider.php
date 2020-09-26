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


use DOMElement;
use LearnWords\Domain\Dictionary\Importer\Provider\Mp3UrlProviderInteface;
use LearnWords\Domain\Dictionary\Mp3Url;
use LearnWords\Domain\Dictionary\Word;

final class WordReferenceMp3Provider implements Mp3UrlProviderInteface
{
    const DOMAIN_NAME = 'https://www.wordreference.com';
    private WordReferenceHtmlPage $htmlPage;

    public function __construct(WordReferenceHtmlPage $htmlPage)
    {
        $this->htmlPage = $htmlPage;
    }


    public function byWord(Word $word): ?Mp3Url
    {
        $crawler = $this->htmlPage->getHtmlByWord($word);
        $audios = $crawler->filter('#listen_widget audio source');
        $nodes = $audios->getIterator()->getArrayCopy();

        $audios = array_map(function (DOMElement $node) {
            return $node->getAttributeNode('src')->value;
        }, $nodes);

        $filtered = array_filter($audios, function (string $src) {
            return str_contains($src, 'general');
        });

        $url = array_shift($filtered) ?? $audios[0] ?? null;

        if (is_null($url)) {
            return null;
        }

        return new Mp3Url(self::DOMAIN_NAME . $url);
    }
}
