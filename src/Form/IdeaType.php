<?php

namespace App\Form;

use App\Entity\Idea;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IdeaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => 'Your idea',
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'Describe it',
                'config' => ['toolbar' => 'basic'],
            ])
            ->add('author', null, [
                'label' => 'Your username',
            ])
            ->add('category', null, [
                'label' => 'Category',
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Idea::class,
        ]);
    }
}
