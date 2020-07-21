<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Form;

use ArrayIterator;
use ArrayObject;
use LogicException;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Domain\Entity\EntityInterface;
use PlanB\Edge\Infrastructure\Symfony\Form\CompositeDataMapper;
use PlanB\Edge\Infrastructure\Symfony\Form\CompositeFormTypeInterface;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class CompositeDataMapperSpec extends ObjectBehavior
{
    public function let(SerializerInterface $serializer,
                        ConstraintViolationList $constraintViolationList,
                        CompositeFormTypeInterface $objectMapper
    )
    {
        $serializer->implement(DenormalizerInterface::class);

        $constraintViolationList->count()->willReturn(0);
        $constraintViolationList->getIterator()->willReturn(new ArrayIterator([]));


        $this->beConstructedWith($serializer);
        $this->attach($objectMapper);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CompositeDataMapper::class);
    }

    public function it_throws_an_exception_if_serializer_not_implement_denormalizer_interface(SerializerInterface $otherSerializer)
    {

        $this->shouldThrow(LogicException::class)->during('setSerializer', [$otherSerializer]);
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

    public function it_is_able_to_convert_a_forms_array_to_data(SerializerInterface $serializer,
                                                                ConstraintViolationList $constraintViolationList,
                                                                CompositeFormTypeInterface $objectMapper,
                                                                FormInterface $name,
                                                                FormInterface $lastName,
                                                                FormConfigInterface $config)
    {
        $response = new stdClass();

        $validateCall = $objectMapper
            ->validate([
                'name' => 'nombre',
                'lastName' => 'apellido',
            ]);

        $denormalizeCall = $objectMapper->denormalize(
            $serializer,
            [
                'name' => 'nombre',
                'lastName' => 'apellido'
            ],
            [
                ObjectNormalizer::OBJECT_TO_POPULATE => null
            ]);


        $validateCall->willReturn($constraintViolationList);
        $denormalizeCall->willReturn($response);

        $forms = new ArrayObject([
            'name' => $this->configureForm($name, $config, 'nombre'),
            'lastName' => $this->configureForm($lastName, $config, 'apellido')
        ]);

        $this->mapFormsToObject($forms)->shouldReturn($response);

        $denormalizeCall->shouldHaveBeenCalled();
        $validateCall->shouldHaveBeenCalled();
    }

    public function it_is_able_to_validate_a_forms_array_with_errors(ConstraintViolationList $constraintViolationList,
                                                                     ConstraintViolation $constraintViolation,
                                                                     CompositeFormTypeInterface $objectMapper,
                                                                     FormInterface $name,
                                                                     FormInterface $lastName,
                                                                     FormConfigInterface $config)
    {

        $constraintViolation->getPropertyPath()->willReturn('[name]');
        $constraintViolation->getMessage()->willReturn('mensage de error');

        $constraintViolationList->count()->willReturn(1);
        $constraintViolationList->getIterator()->willReturn(new ArrayIterator([
            $constraintViolation->getWrappedObject()
        ]));

        $validateCall = $objectMapper
            ->validate([
                'name' => 'nombre',
                'lastName' => 'apellido',
            ]);

        $validateCall->willReturn($constraintViolationList);

        $forms = new ArrayObject([
            'name' => $this->configureFormWithError($name, $config, 'nombre'),
            'lastName' => $this->configureForm($lastName, $config, 'apellido')
        ]);

        $this->mapFormsToObject($forms)->shouldReturn(null);
        $validateCall->shouldHaveBeenCalled();
    }

    public function it_is_able_to_convert_a_forms_array_to_data_with_not_initialized_entity(SerializerInterface $serializer,
                                                                                            ConstraintViolationList $constraintViolationList,
                                                                                            CompositeFormTypeInterface $objectMapper,
                                                                                            FormInterface $name,
                                                                                            FormInterface $lastName,
                                                                                            FormConfigInterface $config,
                                                                                            EntityInterface $entity)
    {
        $response = new stdClass();

        $validateCall = $objectMapper
            ->validate([
                'name' => 'nombre',
                'lastName' => 'apellido',
            ]);

        $denormalizeCall = $objectMapper->denormalize(
            $serializer,
            [
                'name' => 'nombre',
                'lastName' => 'apellido'
            ],
            [
                ObjectNormalizer::OBJECT_TO_POPULATE => null
            ]);


        $validateCall->willReturn($constraintViolationList);
        $denormalizeCall->willReturn($response);

        $forms = new ArrayObject([
            'name' => $this->configureForm($name, $config, 'nombre'),
            'lastName' => $this->configureForm($lastName, $config, 'apellido')
        ]);

        $this->mapFormsToObject($forms, $entity)->shouldReturn($response);

        $denormalizeCall->shouldHaveBeenCalled();
        $validateCall->shouldHaveBeenCalled();
    }

    public function it_is_able_to_convert_a_forms_array_to_data_with_an_initialized_entity(SerializerInterface $serializer,
                                                                                           ConstraintViolationList $constraintViolationList,
                                                                                           CompositeFormTypeInterface $objectMapper,
                                                                                           FormInterface $name,
                                                                                           FormInterface $lastName,
                                                                                           FormConfigInterface $config)
    {

        $entity = new Entity();
        $response = new stdClass();
        $validateCall = $objectMapper
            ->validate([
                'name' => 'nombre',
                'lastName' => 'apellido',
            ]);

        $denormalizeCall = $objectMapper->denormalize(
            $serializer,
            [
                'name' => 'nombre',
                'lastName' => 'apellido'
            ],
            [
                ObjectNormalizer::OBJECT_TO_POPULATE => $entity
            ]);


        $validateCall->willReturn($constraintViolationList);
        $denormalizeCall->willReturn($response);

        $forms = new ArrayObject([
            'name' => $this->configureForm($name, $config, 'nombre'),
            'lastName' => $this->configureForm($lastName, $config, 'apellido')
        ]);

        $this->mapFormsToObject($forms, $entity)->shouldReturn($response);

        $denormalizeCall->shouldHaveBeenCalled();
        $validateCall->shouldHaveBeenCalled();
    }

    public function it_returns_null_when_validation_fails(ConstraintViolationList $constraintViolationList,
                                                          CompositeFormTypeInterface $objectMapper,
                                                          FormInterface $name,
                                                          FormInterface $lastName,
                                                          FormConfigInterface $config)
    {

        $constraintViolationList->count()->willReturn(1);

        $validateCall = $objectMapper->validate(Argument::any());
        $denormalizeCall = $objectMapper->denormalize(Argument::any(), Argument::any(), Argument::any());

        $validateCall->willReturn($constraintViolationList);

        $forms = new ArrayObject([]);
        $this->mapFormsToObject($forms)->shouldReturn(null);

        $validateCall->shouldHaveBeenCalled();
        $denormalizeCall->shouldNotBeCalled();
    }

    /**
     * @param $form
     * @param $config
     * @param string $value
     */
    private function configureForm($form, $config, string $value): FormInterface
    {
        $config->getMapped()->willReturn(true);

        $form->getData()->willReturn($value);
        $form->isSubmitted()->willReturn(true);
        $form->isSynchronized()->willReturn(true);
        $form->isDisabled()->willReturn(false);
        $form->getConfig()->willReturn($config);

        return $form->getWrappedObject();
    }

    /**
     * @param $form
     * @param $config
     * @param string $value
     */
    private function configureFormWithError($form, $config, string $value): FormInterface
    {
        $config->getMapped()->willReturn(true);

        $form->addError(Argument::type(FormError::class))->willReturn();
        $form->getData()->willReturn($value);
        $form->isSubmitted()->willReturn(true);
        $form->isSynchronized()->willReturn(true);
        $form->isDisabled()->willReturn(false);
        $form->getConfig()->willReturn($config);

        return $form->getWrappedObject();
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

class Entity implements EntityInterface
{

    /**
     * @var EntityId
     */
    private EntityId $id;

    public function __construct()
    {
        $this->id = new EntityId();
    }

    public function getId(): EntityId
    {
        return $this->id;
    }
}
