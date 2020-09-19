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

namespace PlanB\Edge\Infrastructure\Sonata\Configurator;

use PlanB\Edge\Infrastructure\Symfony\Form\AutoContainedFormTypeInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Serializer\SerializerAwareInterface;

interface FormConfiguratorInterface extends ConfiguratorInterface, SerializerAwareInterface, AutoContainedFormTypeInterface
{
    public const TYPE = 'form';

    /**
     * @param FormMapper $formMapper
     * @param string $className
     * @param object|null $subjet
     * @return $this
     */
    public function handle(FormMapper $formMapper, string $className, ?object $subjet): self;
}

