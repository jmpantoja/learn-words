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
use PlanB\Edge\Domain\Event\DomainEventInterface;
use PlanB\Edge\Infrastructure\Symfony\Serializer\Normalizer\DomainEventNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

final class Event
{
    private const NON_ALLOWED_WORDS = ['spec', 'entity', 'document', 'model', 'phpcr', 'couchdocument', 'domain', 'doctrine', 'orm', 'mongodb', 'couchdb'];

    private int $id;

    private string $name;

    private string $event;

    private DateTimeInterface $date;

    public function __construct(DomainEventInterface $domainEvent)
    {
        $this->setName(get_class($domainEvent));
        $this->setEvent($domainEvent);
        $this->date = $domainEvent->when();
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

    private function setEvent(DomainEventInterface $domainEvent): self
    {
        $this->event = $this->serialize($domainEvent);
        return $this;
    }

    /**
     * @param DomainEventInterface $event
     * @return string
     */
    private function serialize(DomainEventInterface $event): string
    {
        $encoder = [new JsonEncoder()];
        $normalizer = [new PropertyNormalizer()];

        $serializer = new Serializer($normalizer, $encoder);
        return $serializer->serialize($event, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['when']
        ]);
    }


    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function event(): string
    {
        return $this->event;
    }

    public function eventAsArray(): array
    {
        return json_decode($this->event(), true);
    }

    /**
     * @return DateTimeInterface
     */
    public function date(): DateTimeInterface
    {
        return $this->date;
    }
}
