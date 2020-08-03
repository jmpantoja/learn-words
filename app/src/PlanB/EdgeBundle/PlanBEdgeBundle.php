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

namespace PlanB\EdgeBundle;


use PlanB\EdgeBundle\DependencyInjection\Compiler\DBALTypesCompiler;
use PlanB\EdgeBundle\DependencyInjection\Compiler\FormDataMapperCompiler;
use PlanB\EdgeBundle\DependencyInjection\Compiler\SonataAdminCompiler;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class PlanBEdgeBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new SonataAdminCompiler(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 10);
    }
}
