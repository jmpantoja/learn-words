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

namespace LearnWords\Domain\Dictionary;


use LearnWords\Domain\Dictionary\Traits\NormalizerTrait;
use PlanB\Edge\Domain\Validator\ValidableTrait;

class Example
{
    use ValidableTrait;
    use NormalizerTrait;

    private string $sample;
    private string $translation;

    public function __construct(string $sample, string $translation)
    {
        $sample = $this->normalize($sample);
        $translation = $this->normalize($translation);

        $this->ensure(get_defined_vars());
        $this->sample = $sample;
        $this->translation = $translation;
    }

    public static function getConstraints()
    {
        return new Constraints\Example();
    }

    /**
     * @return string
     */
    public function getSample(): string
    {
        return $this->sample;
    }

    /**
     * @return string
     */
    public function getTranslation(): string
    {
        return $this->translation;
    }

}
