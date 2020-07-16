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

namespace LearnWords\Term\Infrastructure\Normalizer;


use LearnWords\Term\Domain\Model\Lang;
use LearnWords\Term\Domain\Model\Word;
use PlanB\Edge\Infrastructure\Symfony\Normalizer\Denormalizer;

final class WordDenormalizer extends Denormalizer
{

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return $type === Word::class;
    }

    protected function mapToObject($data): object
    {
        if ($data instanceof Word) {
            return $data;
        }

        return new Word(...[
            $data['word'],
            $this->partial($data['lang'], Lang::class)
        ]);
    }
}
