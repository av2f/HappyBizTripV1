<?php

namespace App\Form;

use App\Entity\UserInfo;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          ->add('gender', ChoiceType::class, [
            'label' => 'form.label.gender',
            'label_attr' => ['class' => 'label-profile'],
            'required' => false,
            'choices' => [
                'form.choice.woman' => 'W',
                'form.choice.man' => 'M'
            ]  
          ])
          ->add('situation', ChoiceType::class, [
            'label' => 'form.label.situation',
            'label_attr' => ['class' => 'label-profile'],
            'required' => false,
            'choices' => [
                'form.choice.single' => 'S',
                'form.choice.couple' => 'C',
                'form.choice.keep' => 'K'
            ]
          ])
          ->add('profession', TextType::class, [
            'label' => 'form.label.profession',
            'label_attr' => ['class' => 'label-profile'],
            'attr' => [
                'class' => 'form-control-sm',
                'placeholder' => 'form.placeholder.profession'
            ]

          ])
          ->add('company', TextType::class, [
              'label' => 'form.label.company',
              'label_attr' => ['class' => 'label-profile'],
              'attr' => [
                  'class' => 'form-control-sm',
                  'placeholder' => 'form.placeholder.company'
              ]
          ])
          ->add('description', CKEditorType::class) 
          ->add('phoneNumber', TextType::class, [
            'label' => 'form.label.company',
            'label_attr' => ['class' => 'label-profile'],
            'attr' => [
                'class' => 'form-control-sm',
                'placeholder' => 'form.placeholder.company'
            ]
          ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserInfo::class,
        ]);
    }
}
