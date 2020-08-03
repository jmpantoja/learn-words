<?php

namespace spec\PlanB\Edge\Infrastructure\Sonata\Admin;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Application\UseCase\DeleteCommand;
use PlanB\Edge\Application\UseCase\SaveCommand;
use PlanB\Edge\Application\UseCase\WriteCommandInterface;
use PlanB\Edge\Domain\Entity\Dto;
use PlanB\Edge\Infrastructure\Sonata\Admin\Admin;
use PlanB\Edge\Infrastructure\Sonata\Configurator\DatagridConfiguratorInterface;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use Prophecy\Argument;
use Sonata\AdminBundle\Builder\FormContractorInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Model\ModelManagerInterface;
use stdClass;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AdminSpec extends ObjectBehavior
{
    public function let(FormConfiguratorInterface $formConfigurator,
                        DatagridConfiguratorInterface $datagridConfigurator,
                        ModelManagerInterface $modelManager,
                        SerializerInterface $serializer)
    {
        $this->beAnInstanceOf(Dummy::class);
        $this->beConstructedWith('admin.code', 'admin.className');

        $this->setFormConfigurator($formConfigurator);
        $this->setDatagridConfigurator($datagridConfigurator);

        $this->setModelManager($modelManager);
        $this->setSerializer($serializer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Admin::class);
    }

    public function it_is_able_to_configure_a_form(FormConfiguratorInterface $formConfigurator, FormMapper $formMapper)
    {
        $formConfigurator->handle($formMapper, Argument::any())->shouldBeCalled();
        $this->setFormConfigurator($formConfigurator);
        $this->configureFormFields($formMapper);
    }

    public function it_is_able_to_configure_a_datagrid(DatagridConfiguratorInterface $datagridConfigurator, ListMapper $listMapper)
    {
        $datagridConfigurator->handle($listMapper)->shouldBeCalled();

        $this->setDatagridConfigurator($datagridConfigurator);
        $this->configureListFields($listMapper);
    }

    public function it_is_able_to_configure_the_form_builder(FormConfiguratorInterface $formConfigurator,
                                                             FormContractorInterface $contractor,
                                                             FormBuilderInterface $builder)
    {
        $this->setFormContractor($contractor);
        $contractor->getFormBuilder(Argument::cetera())->willReturn($builder);

        $this->getFormBuilder()->shouldReturn($builder);
    }


    public function it_returns_a_correct_base_route_name()
    {
        $this->getBaseRouteName()->shouldReturn('admin_stdclass');
    }

    public function it_returns_a_correct_base_route_pattern()
    {
        $this->getBaseRoutePattern()->shouldReturn('stdclass');
    }

    public function it_ignore_subject_when_it_is_not_from_the_correct_type()
    {
        $subject = new stdClass();
        $this->setSubject($subject);
        $this->setSubject('other type');

        $this->getSubject()->shouldReturn($subject);
    }

    public function it_returns_a_correct_id_when_pass_an_entity(ModelManagerInterface $modelManager)
    {
        $entity = new stdClass();
        $modelManager->getNormalizedIdentifier($entity)->willReturn('id');
        $this->id($entity)->shouldReturn('id');
    }

    public function it_returns_a_correct_id_when_pass_a_dto(ModelManagerInterface $modelManager, Dto $dto)
    {
        $entity = new stdClass();
        $this->setSubject($entity);

        $modelManager->getNormalizedIdentifier($entity)->willReturn('id');
        $this->id($dto)->shouldReturn('id');
    }

    public function it_is_able_to_create_a_new_entity_from_entity(ModelManagerInterface $modelManager)
    {

        $entity = new stdClass();
        $modelManager->create($entity)->shouldBeCalled();

        $this->create($entity);
    }

    public function it_is_able_to_create_a_new_entity_from_dto(Dto $dto, ModelManagerInterface $modelManager)
    {

        $entity = new stdClass();
        $dto->create()->willReturn($entity);

        $modelManager->create($entity)->shouldBeCalled();

        $this->create($dto);
    }

    public function it_is_able_to_create_a_new_update_from_entity(ModelManagerInterface $modelManager)
    {

        $entity = new stdClass();
        $modelManager->update($entity)->shouldBeCalled();

        $this->update($entity);
    }

    public function it_is_able_to_update_a_new_entity_from_dto(Dto $dto, ModelManagerInterface $modelManager)
    {

        $entity = new stdClass();
        $dto->update(Argument::cetera())->willReturn($entity);

        $modelManager->update($entity)->shouldBeCalled();

        $this->update($dto);
    }

    public function it_returns_the_last_word_of_the_classname_like_to_string(){

        $this->toString(new stdClass())->shouldReturn('stdClass');
    }

}

class Dummy extends Admin
{
    public function configureFormFields(FormMapper $formMapper): void
    {
        parent::configureFormFields($formMapper);
    }

    public function configureListFields(ListMapper $listMapper): void
    {
        parent::configureListFields($listMapper); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritDoc
     */
    public function defineFormBuilder(FormBuilderInterface $formBuilder)
    {
        return $formBuilder;
    }

    public function createObjectSecurity($object)
    {
    }

    public function getClass()
    {
        return stdClass::class;
    }

    public function getDtoClass(): string
    {
        return 'DTO_CLASS';
    }
}
