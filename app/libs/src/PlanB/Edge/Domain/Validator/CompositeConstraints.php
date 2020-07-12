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

namespace PlanB\Edge\Domain\Validator;

use PlanB\Edge\Domain\Validator\Exception\NonExistentFieldException;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Existence;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Required;

final class CompositeConstraints implements ConstraintsDefinition
{
    private array $options = [
        'allowExtraFields' => true,
        'allowMissingFields' => false,
        'extraFieldsMessage' => 'This field was not expected.',
        'missingFieldsMessage' => 'This field is missing.',
        'fields' => []
    ];

    public function toArray(): array
    {
        return $this->options;
    }

    public function allowExtraFields(bool $allowExtraFields = true): self
    {
        $this->options['allowExtraFields'] = $allowExtraFields;
        return $this;
    }

    public function allowMissingFields(bool $allowMissingFields = true): self
    {
        $this->options['allowMissingFields'] = $allowMissingFields;
        return $this;
    }

    public function extraFieldsMessage(string $message): self
    {
        $this->options['extraFieldsMessage'] = $message;
        return $this;
    }

    public function missingFieldsMessage(string $message): self
    {
        $this->options['missingFieldsMessage'] = $message;
        return $this;
    }

    public function required(string $name, array $constraints = []): self
    {
        $this->options['fields'][$name] = new Required($constraints);
        return $this;
    }

    public function optional(string $name, array $constraints = []): self
    {
        $this->options['fields'][$name] = new Optional($constraints);
        return $this;
    }

    public function get(string $name): Existence
    {
        if (!isset($this->options['fields'][$name])) {
            $available = array_keys($this->options['fields']);
            throw new NonExistentFieldException($name, $available);
        }
        return $this->options['fields'][$name];
    }

    public function constraints(): array
    {
        return [new Collection($this->options)];
    }
}
