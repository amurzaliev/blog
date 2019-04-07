<?php

namespace App\Admin;

use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PostAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', TextType::class, [
                'label' => 'Навзание'
            ])
            ->add('slug', TextType::class, [
                'label' => 'URL'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Описание'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Контент'
            ])
            ->add('keywords', TextType::class, [
                'label' => 'Ключевые слова (через запятую)'
            ])
            ->add('deleted', CheckboxType::class, [
                'label' => 'Удалено',
                'required' => false
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email'
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('author');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('title')
            ->add('author')
            ->add('slug')
            ->add('deleted')
            ->add('createdAt');
    }
}