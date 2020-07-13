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

namespace LearnWords\Term\Infrastructure\Doctrine\DBAL\Type;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use LearnWords\Term\Domain\Model\Lang;
use PlanB\Edge\Infrastructure\Doctrine\DBAL\Type\EnumType;

final class LangType extends EnumType
{
    /**
     * @inheritDoc
     */
    public function byKey(string $value, AbstractPlatform $platform): Lang
    {
        return Lang::byKey($value);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'lang';
    }
}