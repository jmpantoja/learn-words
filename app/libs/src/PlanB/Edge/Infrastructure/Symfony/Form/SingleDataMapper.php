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

namespace PlanB\Edge\Infrastructure\Symfony\Form;


use PlanB\Edge\Infrastructure\Symfony\Form\Exception\SingleMappingFailedException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

final class SingleDataMapper implements DataTransformerInterface
{

    private SingleToObjectMapperInterface $objectMapper;
    private array $options = [];

    private ValidatorInterface $validator;

    public function __construct(SingleToObjectMapperInterface $objectMapper, array $options = [])
    {
        $this->objectMapper = $objectMapper;
        $this->options = $options;
        $this->validator = (new ValidatorBuilder())->getValidator();
    }

    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        return (string)$value;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        $this->validate($value);
        return $this->objectMapper->mapValueToObject($value);
    }

    /**
     * @param array $data
     * @return ConstraintViolationListInterface
     */
    private function validate($data): bool
    {
//        $builder = new SingleConstraints();
//        $this->objectMapper->buildConstraints($builder, $this->options);
//        $constraints = $builder->build();
//
//
//
        $violations = $this->objectMapper->validate($data);

        if (0 === $violations->count()) {
            return true;
        }

        throw new SingleMappingFailedException($violations);
    }
}
