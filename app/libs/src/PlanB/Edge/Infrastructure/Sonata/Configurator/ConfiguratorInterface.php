<?php

namespace PlanB\Edge\Infrastructure\Sonata\Configurator;

interface ConfiguratorInterface
{
    public function attachTo(): string;

    public function configure();
}
