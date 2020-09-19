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

use Exception;
use PlanB\Edge\Application\UseCase\UseCaseInterface;
use PlanB\Edge\Domain\DataPersister\DataPersisterInterface;
use PlanB\Edge\Domain\Validator\ValidatorAwareInterface;
use PlanB\Edge\Infrastructure\Sonata\Admin\Admin;
use PlanB\Edge\Infrastructure\Sonata\Configurator\DatagridConfiguratorInterface;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\FormAwareInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\SerializerFormAwareInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Yaml\Yaml;

class PlanBEdgeExtension extends Extension implements PrependExtensionInterface
{

    /**
     * Loads a specific configuration.
     *
     * @param mixed[] $configs
     * @param ContainerBuilder $container
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {

        $container->registerForAutoconfiguration(UseCaseInterface::class)
            ->addTag('tactician.handler', [
                'typehints' => true
            ]);


        $container->registerForAutoconfiguration(DataPersisterInterface::class)
            ->addTag('planb.data_persister');

        $container->registerForAutoconfiguration(ValidatorAwareInterface::class)
            ->addMethodCall('setValidator', [new Reference('validator')]);

        $container->registerForAutoconfiguration(SerializerAwareInterface::class)
            ->addMethodCall('setSerializer', [new Reference('serializer')]);

        $container->registerForAutoconfiguration(FormConfiguratorInterface::class)
            ->addTag('planb.sonata.admin.configurator', ['type' => FormConfiguratorInterface::TYPE]);

        $container->registerForAutoconfiguration(DatagridConfiguratorInterface::class)
            ->addTag('planb.sonata.admin.configurator', ['type' => DatagridConfiguratorInterface::TYPE]);

        $container->registerForAutoconfiguration(Admin::class)
            ->addTag('planb.sonata.admin');

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(dirname(__DIR__) . '/Resources/config')
        );

        $loader->load('services.yaml');
    }


    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container): void
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

    /**
     * @param string $pathToDir
     * @return mixed[]
     */
    private function loadPackagesConfig(string $pathToDir): array
    {
        $finder = new Finder();
        $finder->name('*.yaml')->in($pathToDir);


        $data = [];
        foreach ($finder as $file) {
            $data[] = Yaml::parseFile($file->getPathname());
        }

        return array_merge(...$data);
    }

}
