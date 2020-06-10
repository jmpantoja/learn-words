<?php

declare(strict_types=1);

namespace LearnWords\ModuleA\Infrastructure\Sonata\Admin;

use PlanB\Edge\Infrastructure\Sonata\Admin\Admin;
use PlanB\Edge\Infrastructure\Sonata\Configurator\FormConfiguratorInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class DummyAdmin extends Admin
{
    protected $baseRouteName = 'hola';

    protected $baseRoutePattern = 'hola';

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('name')
            ->add('id');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('name')
            ->add('id')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('name')
            ->add('id');
    }


}
