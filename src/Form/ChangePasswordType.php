<?php

namespace App\Form;

use App\Form\Model\ChangePassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'help' => 'form.help.password',
                'help_attr' => ['class' => 'label-profile'],
                'attr' => [
                    'class' => 'form-control-sm'
                ]
            ])

            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'user.change.password.match',
                'first_options' => [
                    'help' => 'form.help.password',
                    'help_attr' => ['class' => 'label-profile'],
                    'attr' => [
                        'class' => 'form-control-sm',
                    ]
                ],
                'second_options' => [
                    'help' => 'form.help.password',
                    'help_attr' => ['class' => 'label-profile'],
                    'attr' => [
                        'class' => 'form-control-sm'
                    ]
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChangePassword::class
        ]);
    }
}
