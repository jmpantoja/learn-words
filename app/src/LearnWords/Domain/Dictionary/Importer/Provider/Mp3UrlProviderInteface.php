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

namespace LearnWords\Domain\Dictionary\Importer\Provider;


use LearnWords\Domain\Dictionary\Mp3Url;
use LearnWords\Domain\Dictionary\Word;

interface Mp3UrlProviderInteface
{
    public function byWord(Word $word): ?Mp3Url;
}
