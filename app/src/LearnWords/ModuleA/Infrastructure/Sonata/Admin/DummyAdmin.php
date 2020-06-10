<?php

declare(strict_types=1);

namespace LearnWords\ModuleA\Infrastructure\Sonata\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class DummyAdmin extends AbstractAdmin
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

    protected function configureFormFields(FormMapper $formMapper): void
    {

//        $formMapper->getFormBuilder()->setDataMapper($this);
//        $formMapper->getFormBuilder()->addModelTransformer($this);
//

        $formMapper->add('name', null, [
            'attr' => [
                'style' => 'width:200px'
            ]
        ]);
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('name')
            ->add('id');
    }


}
