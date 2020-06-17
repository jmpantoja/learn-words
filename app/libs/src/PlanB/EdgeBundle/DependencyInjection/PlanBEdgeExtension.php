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

use PlanB\Edge\Application\UseCase\UseCaseInterface;
use PlanB\Edge\Infrastructure\Sonata\Admin\AdminInterface;
use PlanB\Edge\Infrastructure\Sonata\Configurator\DatagridConfiguratorInterface;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

class PlanBEdgeExtension extends Extension implements PrependExtensionInterface
{

    /**
     * Loads a specific configuration.
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $container->registerForAutoconfiguration(UseCaseInterface::class)->addTag('tactician.handler', [
            'typehints' => true
        ]);

        $container->registerForAutoconfiguration(FormConfiguratorInterface::class)
            ->addTag('planb.admin.configurator', ['type' => FormConfiguratorInterface::TYPE]);

        $container->registerForAutoconfiguration(DatagridConfiguratorInterface::class)
            ->addTag('planb.admin.configurator', ['type' => DatagridConfiguratorInterface::TYPE]);

        $container->registerForAutoconfiguration(AdminInterface::class)
            ->addTag('planb.admin');

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(dirname(__DIR__) . '/Resources/config')
        );
        $loader->load('services.yaml');
    }


    /**
     * Allow an extension to prepend the extension configurations.
     */
    public function prepend(ContainerBuilder $container)
    {

        $extensions = array_keys($container->getExtensions());

        $pathToPackages = realpath(__DIR__ . '/../Resources/config/packages');
        $config = $this->loadPackagesConfig($pathToPackages);

        foreach ($extensions as $extension) {

            if (isset($config[$extension])) {
                $container->prependExtensionConfig($extension, $config[$extension]);
            }
        }
    }

    private function loadPackagesConfig(string $pathToDir)
    {
        $finder = new Finder();
        $finder->name('*.yaml')->in($pathToDir);


        foreach ($finder as $file) {
            $data[] = Yaml::parseFile($file->getPathname());
        }

        return array_merge(...$data);
    }

}
