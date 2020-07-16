<?php

namespace spec\PlanB\Edge\Infrastructure\Sonata\Admin;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Entity\EntityInterface;
use PlanB\Edge\Infrastructure\Sonata\Admin\RouteBaseUtils;

class RouteBaseUtilsSpec extends ObjectBehavior
{
    public function it_is_initializable_from_an_object(EntityInterface $entity)
    {
        $this->beConstructedThrough('fromEntity', [$entity]);
        $this->shouldHaveType(RouteBaseUtils::class);
    }

    public function it_returns_base_route_name()
    {
        $className = 'Namespace\Bundle\NamedBundle\Domain\Entity\Name';
        $this->beConstructedThrough('fromClassName', [$className]);
        $this->getBaseRouteName()->shouldReturn('admin_namespace_name');
    }

    public function it_returns_base_route_pattern()
    {
        $className = 'Namespace\Bundle\NamedBundle\Domain\Entity\Name';
        $this->beConstructedThrough('fromClassName', [$className]);

        $this->getBaseRoutePattern()->shouldReturn('namespace/name');
    }

}

