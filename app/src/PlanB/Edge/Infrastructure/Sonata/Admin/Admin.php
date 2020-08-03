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

namespace PlanB\Edge\Infrastructure\Sonata\Admin;


use PlanB\Edge\Domain\Entity\Dto;
use PlanB\Edge\Infrastructure\Sonata\Configurator\DatagridConfiguratorInterface;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use PlanB\Edge\Infrastructure\Sonata\Doctrine\ManagerCommandFactoryInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Serializer\SerializerInterface;

abstract class Admin extends AbstractAdmin implements AdminInterface
{
    private SerializerInterface $serializer;

    private FormConfiguratorInterface $formConfigurator;

    private DatagridConfiguratorInterface $datagridConfigurator;

    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

    /**
     * @inheritDoc
     */
    public function getBaseRouteName()
    {
        return RouteBaseUtils::fromClassName($this->getClass())
            ->getBaseRouteName();
    }

    /**
     * @inheritDoc
     */
    public function getBaseRoutePattern()
    {
        return RouteBaseUtils::fromClassName($this->getClass())
            ->getBaseRoutePattern();
    }

    public function getFormBuilder()
    {
        $this->formOptions['data_class'] = $this->getDtoClass();

        $formBuilder = $this->getFormContractor()->getFormBuilder(
            $this->getUniqid(),
            $this->formOptions
        );

        $this->defineFormBuilder($formBuilder);
        return $formBuilder;
    }

    abstract public function getDtoClass(): string;

    /**
     * @param FormConfiguratorInterface $formConfigurator
     * @return $this
     */
    public function setFormConfigurator(FormConfiguratorInterface $formConfigurator): self
    {
        $this->formConfigurator = $formConfigurator;
        return $this;
    }

    public function setDatagridConfigurator(DatagridConfiguratorInterface $datagridConfigurator): AdminInterface
    {
        $this->datagridConfigurator = $datagridConfigurator;
        return $this;
    }

    public function id($model)
    {
        if ($model instanceof Dto) {
            $model = $this->getSubject();
        }

        return $this->getNormalizedIdentifier($model);
    }

    public function setSubject($subject): void
    {
        if (!is_a($subject, $this->getClass())) {
            return;
        }

        parent::setSubject($subject);
    }

    public function create($object)
    {
        $entity = $object;
        if ($object instanceof Dto) {
            $entity = $object->create();
        }
        return parent::create($entity);

    }

    public function update($object)
    {
        $entity = $object;
        if ($object instanceof Dto) {
            $entity = $object->update($this->getSubject());
        }
        return parent::update($entity);
    }

    public function toString($object)
    {
        $pieces = explode('\\', $this->getClass());
        return array_pop($pieces);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $this->formConfigurator
            ->handle($formMapper, $this->getSubject());
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $this->datagridConfigurator
            ->handle($listMapper);
    }
}


