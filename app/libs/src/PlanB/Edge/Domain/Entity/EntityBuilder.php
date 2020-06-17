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


use PlanB\Edge\Domain\Entity\Exception\EntityBuilderException;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use TypeError;

abstract class EntityBuilder
{
    public function withData(array $values): self
    {
        foreach ($values as $key => $value) {
            $this->withKeyValue($key, $value);
        }

        return $this;
    }

    /**
     * @param $key
     * @param $value
     */
    private function withKeyValue($key, $value): self
    {

        try {
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            $propertyAccessor->setValue($this, $key, $value);
        } catch (NoSuchPropertyException $exception) {
            throw EntityBuilderException::noSuchProperty($key);
        } catch (TypeError $exception) {
            throw EntityBuilderException::typeError($key, $value);
        }

        return $this;
    }


    abstract public function build(): EntityInterface;


}
