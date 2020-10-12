<?php

namespace spec\LearnWords\Infrastructure\Domain\Dictionary\Importer\Reader;

use LearnWords\Domain\Dictionary\Importer\Reader\ReaderInterface;
use LearnWords\Infrastructure\Domain\Dictionary\Importer\Reader\EntriesCSVReader;
use org\bovigo\vfs\vfsStream;
use PhpSpec\ObjectBehavior;
use SplFileInfo;

class EntriesCSVReaderSpec extends ObjectBehavior
{
    public function let(SplFileInfo $fileInfo)
    {
        $structure = [
            'csv' => [
                'input.csv' => implode("\n", [
                    'goodbye,,saludos,a1,basico',
                    'good night,cuando vamos a dormir,saludos,a1',
                    'good afternoon,hasta la hora de la cena,saludos,a1,basico',
                    'car,,transporte,b1',
                    'boat,,transporte,b1',
                ]),
            ]
        ];

        vfsStream::setup('root', null, $structure);

        $fileInfo->getRealPath()->willReturn('vfs://root/csv/input.csv');
        $this->beConstructedWith($fileInfo);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(EntriesCSVReader::class);
        $this->shouldHaveType(ReaderInterface::class);
    }

    public function it_iterate_correctly(SplFileInfo $fileInfo)
    {
        $data = $this->getIterator();

        $data[0]->shouldIterateLike([
            'word' => 'goodbye',
            'notes' => '',
            'tags' => ['saludos', 'a1', 'basico']
        ]);

        $data[1]->shouldIterateLike([
            'word' => 'good night',
            'notes' => 'cuando vamos a dormir',
            'tags' => ['saludos', 'a1']
        ]);

        $data[2]->shouldIterateLike([
            'word' => 'good afternoon',
            'notes' => 'hasta la hora de la cena',
            'tags' => ['saludos', 'a1', 'basico']
        ]);

        $data[3]->shouldIterateLike([
            'word' => 'car',
            'notes' => '',
            'tags' => ['transporte', 'b1']
        ]);

        $data[4]->shouldIterateLike([
            'word' => 'boat',
            'notes' => '',
            'tags' => ['transporte', 'b1']
        ]);

    }
}
