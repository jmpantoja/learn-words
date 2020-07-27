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


use Symfony\Component\Form\DataMapperInterface;

interface CompositeDataMapperInterface extends DataMapperInterface
{
    public function attach(CompositeFormTypeInterface $formType): self;


}
