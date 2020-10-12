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


use LearnWords\Domain\Dictionary\Importer\Provider\QuestionProviderInterface;
use LearnWords\Domain\Dictionary\Word;

final class WordReferenceProvider implements QuestionProviderInterface
{
    private WordReferenceHtmlPage $htmlPage;

    public function __construct(WordReferenceHtmlPage $htmlPage)
    {
        $this->htmlPage = $htmlPage;
    }

    public function byWord(Word $word): array
    {
        $crawler = $this->htmlPage->getHtmlByWord($word);

        $table = $crawler->filter('table.WRD')->first();
        $rows = $table->filter('tr');
        $total = $rows->count();

        $questions = [];
        $id = null;

        for ($i = 2; $i < $total; $i++) {
            $row = $rows->eq($i);
            $id = $row->attr('id') ?? $id;

            $questions[$id] = $this->getQuestionBuilder($questions, $id);
            $questions[$id]->process($row);
        }

        $questions = array_map(function (WordReferenceQuestionContext $context) {
            return $context->getQuestion();
        }, array_values($questions));

        return array_filter($questions);
    }

    private function getQuestionBuilder(array $questions, string $id)
    {
        return $questions[$id] ?? new WordReferenceQuestionContext();
    }
}
