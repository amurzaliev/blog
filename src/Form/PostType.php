<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Название статьи'
            ])
            ->add('slug', TextType::class, [
                'label' => 'URL'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Контент'
            ])
            ->add('keywords', TextareaType::class, [
                'label' => 'Ключевые слова'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Описание'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
