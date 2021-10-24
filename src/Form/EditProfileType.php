<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\DateStringToDateTransformer;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class EditProfileType extends AbstractType
{
    private $transformer;

    public function __construct(DateStringToDateTransformer $transformer) {
        $this->transformer = $transformer;
    }
    
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
            ->add('firstName', TextType::class, [
                'help' => 'form.help.firstName',
                'help_attr' => ['class' => 'label-profile'],
                'label' => 'form.label.firstname',
                'label_attr' => ['class' => 'label-profile'],
                'attr' => [
                    'class' => 'form-control-sm',
                    'placeholder' => 'form.placeholder.firstname'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'form.label.lastname',
                'label_attr' => ['class' => 'label-profile'],
                'attr' => [
                    'class' => 'form-control-sm',
                    'placeholder' => 'form.placeholder.lastname'
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control-sm',
                    'placeholder' => 'form.placeholder.email'
                ]
            ])
            ->add('birthDate', TextType::class, [
                'help' => 'form.help.birthDate',
                'help_attr' => ['class' => 'label-profile'],
                'attr' => ['class' => 'js-datepicker form-control-sm'],
                'help_attr' => ['class' => 'form-date-help']

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
            ->add('listInterest', HiddenType::class, [
                'mapped' => false,
                'attr' => ['id' => 'listInterest']
            ])
        ;
        // manage date format
        $builder->get('birthDate')
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
