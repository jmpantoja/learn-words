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

namespace PlanB\Edge\Domain\Entity;

use Ramsey\Uuid\Uuid;

class EntityId
{
    protected string $uuid;

    /**
     * @param string $uuid
     * @throws \Exception
     */
    public function __construct(string $uuid = null)
    {
        if (is_null($uuid)) {
            $this->uuid = (string)Uuid::uuid1();
            return;
        }

        $this->uuid = (string)Uuid::fromString($uuid);
    }

    public static function fromString(string $uuid): self
    {
        return new static($uuid);
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param EntityId $otherId
     *
     * @return bool
     */
    public function equals(EntityId $otherId)
    {
        return (string)$this === (string)$otherId;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->uuid;
    }
}
