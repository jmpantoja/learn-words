<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PlanB\EdgeBundle\DependencyInjection;

use PlanB\Edge\Infrastructure\Sonata\Admin\AdminInterface;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class PlanBEdgeExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $container->registerForAutoconfiguration(FormConfiguratorInterface::class)
            ->addTag('planb.admin.configurator', ['type' => FormConfiguratorInterface::TYPE]);

        $container->registerForAutoconfiguration(AdminInterface::class)
            ->addTag('planb.admin');


//        $container->registerForAutoconfiguration(UseCaseInterface::class)->addTag('tactician.handler', [
//            'typehints' => true
//        ]);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(dirname(__DIR__) . '/Resources/config')
        );
        $loader->load('services.yaml');
    }

}
