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
use Ramsey\Uuid\UuidInterface;

class EntityId
{

    private int $id;
    private Uuid $uuid;

    /**
     * @param string $uuid
     * @throws \Exception
     */
    public function __construct(string $uuid = null)
    {
        if (is_null($uuid)) {
            $this->uuid = Uuid::uuid4();
            return;
        }

        $this->uuid = Uuid::fromString($uuid);
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    public function uuid(): UuidInterface
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
        return $this->uuid()->equals($otherId->uuid());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->uuid();
    }
}
