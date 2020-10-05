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

class Wording
{
    use ValidableTrait;
    use NormalizerTrait;

    private string $wording;
    private string $description;

    public function __construct(string $wording, string $description)
    {
        $wording = $this->normalize($wording);

        $description = $this->normalize($description);

        $this->ensure(get_defined_vars());

        $this->wording = $wording;
        $this->description = $description;
    }

    public static function getConstraints()
    {
        return new Constraints\Wording();
    }

    /**
     * @return string
     */
    public function getWording(): string
    {
        return $this->wording;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public function __toString()
    {
        return $this->wording;
    }


}
