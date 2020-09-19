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


use PlanB\Edge\Domain\Validator\ValidatorAwareInterface;
use Symfony\Component\Form\FormInterface;

interface SelfValidatedFormTypeInterface extends ValidatorAwareInterface
{
    /**
     * @param mixed $data
     * @param FormInterface $form
     * @return mixed
     */
    public function validate($data, FormInterface $form);
}
