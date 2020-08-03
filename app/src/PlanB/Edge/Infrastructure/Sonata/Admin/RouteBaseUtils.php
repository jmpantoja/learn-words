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

namespace PlanB\Edge\Infrastructure\Sonata\Admin;

use PlanB\Edge\Domain\Entity\EntityInterface;

final class RouteBaseUtils
{
    private const NON_ALLOWED_WORDS = ['entity', 'document', 'model', 'phpcr', 'couchdocument', 'domain', 'doctrine', 'orm', 'mongodb', 'couchdb'];

    private array $pieces = [];

    private function __construct(string $className)
    {
        $pieces = explode('\\', strtolower($className));
        $this->pieces = array_filter($pieces, [$this, 'filter']);
    }

    public static function fromEntity(EntityInterface $entity): self
    {
        return static::fromClassName(get_class($entity));
    }

    public static function fromClassName(string $className): self
    {
        return new self($className);
    }

    public function getBaseRouteName(): string
    {
        $className = implode('_', $this->pieces);
        return sprintf('admin_%s', $className);
    }

    public function getBaseRoutePattern(): string
    {
        return implode('/', $this->pieces);
    }

    private function filter(string $piece): bool
    {
        if (in_array($piece, static::NON_ALLOWED_WORDS)) {
            return false;
        }
        return !str_ends_with($piece, 'bundle');
    }


}
