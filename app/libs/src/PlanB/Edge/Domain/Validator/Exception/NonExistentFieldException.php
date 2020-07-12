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

namespace PlanB\Edge\Domain\Validator\Exception;


use InvalidArgumentException;

final class NonExistentFieldException extends InvalidArgumentException
{
    public function __construct(string $name, array $available = [])
    {
        $message = $this->parseMessage($name, $available);
        parent::__construct($message);
    }

    /**
     * @param string $name
     * @param array $available
     * @return string
     */
    private function parseMessage(string $name, array $available = []): string
    {
        if (empty($available)) {
            return sprintf('El campo "%s" no existe.', $name);
        }

        return sprintf('El campo "%s" no existe. (%s)', ...[
            $name,
            implode(', ', $available)
        ]);
    }

}
