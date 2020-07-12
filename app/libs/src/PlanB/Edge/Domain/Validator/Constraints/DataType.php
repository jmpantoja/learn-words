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

namespace PlanB\Edge\Domain\Validator\Constraints;


use Symfony\Component\Validator\Constraints\Type;

final class DataType extends Type
{
    public $message = 'This value is not of the correct type.';
    public $allowNull = false;

}
