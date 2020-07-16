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


use LearnWords\Term\Domain\Model\Term;
use LearnWords\Term\Domain\Model\TermId;
use LearnWords\Term\Domain\Model\Word;
use PlanB\Edge\Infrastructure\Symfony\Normalizer\Denormalizer;

final class TermDenormalizer extends Denormalizer
{
    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return $type === Term::class;
    }

    protected function mapToObject($data, ?Term $term = null): object
    {
        $word = $this->partial($data['word'], Word::class);

        if (is_null($term)) {
            return new Term(new TermId(), $word);
        }
        return $term->update($word);
    }
}
