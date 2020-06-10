<?php

namespace spec\PlanB\EdgeBundle;

use PhpSpec\ObjectBehavior;
use PlanB\EdgeBundle\DependencyInjection\Compiler\SonataAdminCompiler;
use PlanB\EdgeBundle\PlanBEdgeBundle;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PlanBEdgeBundleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PlanBEdgeBundle::class);
    }

    public function it_should_add_compiler_to_container(ContainerBuilder $container)
    {
        $this->build($container);
        $container
            ->addCompilerPass(Argument::type(SonataAdminCompiler::class), PassConfig::TYPE_BEFORE_OPTIMIZATION, 10)
            ->shouldBeCalled();
    }
}
