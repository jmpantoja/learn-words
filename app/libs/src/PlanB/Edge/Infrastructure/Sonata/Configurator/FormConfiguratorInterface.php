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

use PlanB\Edge\Infrastructure\Sonata\Doctrine\ManagerCommandFactoryInterface;
use Sonata\AdminBundle\Form\FormMapper;

interface FormConfiguratorInterface extends ConfiguratorInterface
{
    public const TYPE = 'form';

    /**
     * @param ManagerCommandFactoryInterface $commandFactory
     */
    public function setCommandFactory(ManagerCommandFactoryInterface $commandFactory): self;

    /**
     * @param FormMapper $formMapper
     * @param object|null $subject
     * @return void
     */
    public function handle(FormMapper $formMapper, ?object $subject): self;

}

