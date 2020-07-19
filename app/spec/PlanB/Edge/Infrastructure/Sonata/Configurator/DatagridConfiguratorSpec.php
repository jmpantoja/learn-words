<?php

namespace spec\PlanB\Edge\Infrastructure\Sonata\Configurator;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Sonata\Configurator\ConfiguratorInterface;
use PlanB\Edge\Infrastructure\Sonata\Configurator\DatagridConfigurator;
use PlanB\Edge\Infrastructure\Sonata\Configurator\DatagridConfiguratorInterface;
use Prophecy\Argument;
use Sonata\AdminBundle\Datagrid\ListMapper;

class DatagridConfiguratorSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ConcreteDatagridConfigurator::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DatagridConfigurator::class);
        $this->shouldHaveType(DatagridConfiguratorInterface::class);
        $this->shouldHaveType(ConfiguratorInterface::class);
    }

    public function it_is_able_to_add_a_column_to_a_list_mapper(ListMapper $listMapper)
    {
        $this->handle($listMapper);

        $listMapper->addIdentifier('ID', null, Argument::type('array'))->shouldHaveBeenCalled();
        $listMapper->add('NAME', null, Argument::type('array'))->shouldHaveBeenCalled();
    }
}

class ConcreteDatagridConfigurator extends DatagridConfigurator
{
    public function attachTo(): string
    {
        return 'className';
    }

    public function configure()
    {
        $this->addIdentifier('ID')
            ->add('NAME');
    }
}
