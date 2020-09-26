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


use PlanB\Edge\Domain\Validator\ValidableTrait;

final class Mp3Url
{
    use ValidableTrait;

    private string $url;

    public function __construct(string $url)
    {
        $this->ensure($url);
        $this->url = $url;
    }

    public static function getConstraints()
    {
        return new Constraints\Mp3Url();
    }

    public function getUrl()
    {
        return $this->url;
    }
}
