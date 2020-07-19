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


use DateTimeInterface;
use Laminas\Filter\Word\CamelCaseToUnderscore;
use PlanB\Edge\Infrastructure\Symfony\Serializer\Normalizer\DomainEventNormalizer;

final class Event
{
    private const NON_ALLOWED_WORDS = ['spec', 'entity', 'document', 'model', 'phpcr', 'couchdocument', 'domain', 'doctrine', 'orm', 'mongodb', 'couchdb'];

    private int $id;

    private string $name;

    private string $event;

    private DateTimeInterface $date;

    public function __construct(string $name, string $event, DateTimeInterface $date)
    {
        $this->setName($name);
        $this->setEvent($event);
        $this->date = $date;
    }

    /**
     * @param string $name
     */
    private function setName(string $name): self
    {

        $pieces = explode('\\', $name);

        $pieces = array_filter($pieces, function (string $item) {
            $item = strtolower($item);
            return !in_array($item, self::NON_ALLOWED_WORDS);
        });

        $filter = new CamelCaseToUnderscore();

        $namespace = array_shift($pieces);
        $eventName = array_pop($pieces);
        $eventName = $filter->filter($eventName);

        $this->name = sprintf('%s.%s', $namespace, $eventName);

        return $this;
    }

    private function setEvent(string $event): self
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }
}
