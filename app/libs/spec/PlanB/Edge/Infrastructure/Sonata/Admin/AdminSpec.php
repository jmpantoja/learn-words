<?php

namespace spec\PlanB\Edge\Infrastructure\Sonata\Admin;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Application\UseCase\DeleteCommand;
use PlanB\Edge\Application\UseCase\PersistenceCommand;
use PlanB\Edge\Application\UseCase\PersistenceCommandInterface;
use PlanB\Edge\Domain\Entity\EntityInterface;
use PlanB\Edge\Infrastructure\Sonata\Admin\Admin;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use Prophecy\Argument;
use Sonata\AdminBundle\Form\FormMapper;

class AdminSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf(Dummy::class);
        $this->beConstructedWith('admin.code', 'admin.className');

    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Admin::class);
    }

    function it_is_able_to_configure_a_form(FormConfiguratorInterface $formConfigurator, FormMapper $formMapper)
    {
        $formConfigurator->handle($formMapper, Argument::any())->shouldBeCalled();

        $this->setFormConfigurator($formConfigurator);
        $this->configureFormFields($formMapper);
    }

}

class Dummy extends Admin
{
    public function configureFormFields(FormMapper $formMapper): void
    {
        parent::configureFormFields($formMapper);
    }


    public function deleteCommand(EntityInterface $entity): DeleteCommand
    {
    }

    public function saveCommand(array $input, ?EntityInterface $entity): PersistenceCommand
    {
    }
}
