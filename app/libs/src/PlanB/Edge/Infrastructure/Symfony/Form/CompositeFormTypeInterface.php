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
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface CompositeFormTypeInterface
{
    public function setDataMapper(CompositeDataMapperInterface $dataMapper): self;

    public function denormalize(DenormalizerInterface $serializer, array $data, array $context): ?object;

    public function validate(array $data): ConstraintViolationListInterface;

}
