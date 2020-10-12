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

namespace LearnWords\Application\Dictionary\UseCase;

use LearnWords\Domain\Dictionary\Irregular;

final class SaveIrregular
{

    private Irregular  $irregular;

    public function __construct(Irregular $irregular)
    {
        $this->irregular = $irregular;
    }

    /**
     * @return Irregular
     */
    public function getIrregular(): Irregular
    {
        return $this->irregular;
    }


}
