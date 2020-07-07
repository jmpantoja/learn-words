<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form;

use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;
use PlanB\Edge\Infrastructure\Symfony\Form\CompoundDataMapper;
use PlanB\Edge\Infrastructure\Symfony\Form\CompoundToObjectMapperInterface;
use PlanB\Edge\Infrastructure\Symfony\Validator\CompoundBuilder;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormInterface;

class CompoundDataMapperSpec extends ObjectBehavior
{
    public function let(CompoundToObjectMapperInterface $objectMapper)
    {
        $this->beConstructedWith($objectMapper);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CompoundDataMapper::class);
    }

    public function it_is_able_to_map_an_array_to_forms(FormInterface $name,
                                                        FormInterface $lastName,
                                                        FormConfigInterface $config)
    {
        $data = new DataObject();
        $config->getMapped()->willReturn(true);

        $name->getConfig()->willReturn($config);
        $lastName->getConfig()->willReturn($config);

        $this->mapDataToForms($data, [
            'name' => $name,
            'lastName' => $lastName
        ]);

        $name->setData('nombre')->shouldHaveBeenCalledOnce();
        $lastName->setData('apellido')->shouldHaveBeenCalledOnce();
    }

    public function it_is_able_to_map_an_array_to_forms_except_not_mapped_fields(FormInterface $name,
                                                                                 FormInterface $lastName,
                                                                                 FormConfigInterface $nameConfig,
                                                                                 FormConfigInterface $lastNameConfig
    )
    {
        $data = new DataObject();
        $nameConfig->getMapped()->willReturn(true);
        $lastNameConfig->getMapped()->willReturn(false);

        $name->getConfig()->willReturn($nameConfig);
        $lastName->getConfig()->willReturn($lastNameConfig);

        $this->mapDataToForms($data, [
            'name' => $name,
            'lastName' => $lastName
        ]);

        $name->setData('nombre')->shouldHaveBeenCalledOnce();
        $lastName->setData(Argument::any())->shouldNotHaveBeenCalled();
    }

    public function it_dont_set_any_value_when_data_is_empty(FormInterface $name,
                                                             FormInterface $lastName,
                                                             FormConfigInterface $config)
    {
        $data = null;
        $config->getMapped()->willReturn(true);

        $name->getConfig()->willReturn($config);
        $lastName->getConfig()->willReturn($config);

        $this->mapDataToForms($data, [
            'name' => $name,
            'lastName' => $lastName
        ]);

        $name->setData(Argument::any())->shouldNotHaveBeenCalled();
        $lastName->setData(Argument::any())->shouldNotHaveBeenCalled();
    }

    public function it_throws_an_exception_when_data_is_not_an_array_or_an_object(FormInterface $name,
                                                                                  FormInterface $lastName)
    {
        $data = 'escalar';

        $this->shouldThrow(UnexpectedTypeException::class)
            ->duringMapDataToForms($data, [
                'name' => $name,
                'lastName' => $lastName
            ]);
    }

    public function it_is_able_to_convert_a_forms_array_to_data(CompoundToObjectMapperInterface $objectMapper,
                                                                FormInterface $name,
                                                                FormInterface $lastName,
                                                                FormConfigInterface $config)
    {
        $response = new stdClass();
        $objectMapper->mapDataToObject(Argument::any(), Argument::any())
            ->willReturn($response);

        $forms = [
            'name' => $this->configureForm($name, $config, 'nombre'),
            'lastName' => $this->configureForm($lastName, $config, 'apellido')
        ];

        $this->process($forms)->shouldReturn($response);

        $objectMapper
            ->mapDataToObject([
                'name' => 'nombre',
                'lastName' => 'apellido'], null)
            ->shouldHaveBeenCalled();

        $objectMapper
            ->buildConstraints(Argument::type(CompoundBuilder::class), [])
            ->shouldHaveBeenCalled();
    }

    /**
     * @param $form
     * @param $config
     * @param string $value
     */
    private function configureForm($form, $config, string $value): Collaborator
    {
        $config->getMapped()->willReturn(true);

        $form->getData()->willReturn($value);
        $form->isSubmitted()->willReturn(true);
        $form->isSynchronized()->willReturn(true);
        $form->isDisabled()->willReturn(false);
        $form->getConfig()->willReturn($config);

        return $form;
    }

}

class DataObject
{
    public function name()
    {
        return 'nombre';
    }

    public function lastName()
    {
        return 'apellido';
    }
}
