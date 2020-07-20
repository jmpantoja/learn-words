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


use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderFactory;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface SingleFormTypeInterface
{
    public function setDataMapper(SingleDataMapperInterface $dataMapper): self;

    /**
     * @param DenormalizerInterface $serializer
     * @param mixed $data
     * @param mixed[] $context
     * @return object|null
     */
    public function denormalize(DenormalizerInterface $serializer, $data, array $context): ?object;

    /**
     * @param mixed $data
     * @return ConstraintViolationListInterface
     */
    public function validate($data): ConstraintViolationListInterface;
}
