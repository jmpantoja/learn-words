<?php

namespace PlanB\Edge\Infrastructure\Sonata\Configurator;

interface ConfiguratorInterface
{
    static public function attachTo(): string;

    public function configure(): void;
}
