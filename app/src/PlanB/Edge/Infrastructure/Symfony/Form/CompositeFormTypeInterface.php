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
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface CompositeFormTypeInterface
{
    public function setDataMapper(CompositeDataMapperInterface $dataMapper): self;

    /**
     * @param FormSerializerInterface $serializer
     * @param mixed $data
     * @return mixed
     */
    public function normalize(FormSerializerInterface $serializer, $data);

    /**
     * @param FormSerializerInterface $serializer
     * @param mixed $data
     * @param object|null $subject
     * @return mixed
     */
    public function denormalize(FormSerializerInterface $serializer, $data, ?object $subject = null);

    public function validate(array $data): ConstraintViolationListInterface;

}
