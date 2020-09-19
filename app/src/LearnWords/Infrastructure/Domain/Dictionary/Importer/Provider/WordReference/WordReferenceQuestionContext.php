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


use LearnWords\Domain\Dictionary\Example;
use LearnWords\Domain\Dictionary\Wording;
use Symfony\Component\DomCrawler\Crawler;

final class WordReferenceQuestionContext
{

    private ?Wording $wording = null;
    private ?Example $example = null;

    private ?string $sample = null;
    private ?string $translation = null;


    public function process(Crawler $row)
    {
        $this->processWording($row);
        $this->processSample($row);
        $this->processTranslation($row);
    }

    private function processWording(Crawler $row)
    {
        if (null === $row->attr('id')) {
            return;
        }

        $columns = $row->filter('td');

        $wording = $columns->eq(2)->html();
        $description = $columns->eq(1)->html();

        $this->wording = new Wording($wording, $description);
    }

    private function processSample(Crawler $row)
    {
        if ($this->example instanceof Example) {
            return;
        }

        $column = $row->filter('td.FrEx');

        if ($column->count() < 1) {
            return;
        }

        $this->sample = $column->html();
        $this->processExample();
    }

    private function processTranslation(Crawler $row)
    {
        if ($this->example instanceof Example) {
            return;
        }

        $column = $row->filter('td.ToEx');

        if ($column->count() < 1) {
            return;
        }

        $this->translation = $column->html();
        $this->processExample();
    }

    private function processExample()
    {
        if (is_null($this->sample) or is_null($this->translation)) {
            return;
        }

        $this->example = new Example($this->sample, $this->translation);

        $this->sample = null;
        $this->translation = null;
    }

    public function getQuestion(): ?array
    {
        if (is_null($this->wording) or is_null($this->example)) {
            return null;
        }

        return [
            'wording' => $this->wording,
            'example' => $this->example
        ];
    }
}
