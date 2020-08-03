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


use Symfony\Component\Validator\Validator\ValidatorInterface;

interface ValidatorAwareInterface
{
    public function setValidator(ValidatorInterface $validator): self;
}
