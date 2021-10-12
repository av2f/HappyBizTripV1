<?php

namespace App\Form;

use App\Entity\User;
use App\Form\DataTransformer\DateStringToDateTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class HomeRegisterType extends AbstractType
{
    private $transformer;

    public function __construct(DateStringToDateTransformer $transformer) {
        $this->transformer = $transformer;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'help' => 'form.help.firstName',
                'attr' => [
                    'placeholder' => 'form.placeholder.firstName'
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => 'form.placeholder.email']
            ])
            ->add('birthDate', TextType::class, [
                'help' => 'form.help.birthDate',
                'attr' => ['class' => 'js-datepicker'],
                'help_attr' => ['class' => 'form-date-help']
            ])
            ->add('password', PasswordType::class, [
                'help' => 'form.help.password',
                'attr' => ['placeholder' => 'form.placeholder.password']
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
