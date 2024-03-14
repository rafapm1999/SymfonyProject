<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['isSuperadmin']) {
            $choices = [
                'Usuario normal' => 'ROLE_USERNORMAL',
                'Administrador' => 'ROLE_ADMIN',
                'Superadministrador' => 'ROLE_SUPERADMIN'
            ];
        } else {
            $choices = [
                'Usuario normal' => 'ROLE_USERNORMAL'
            ];
        }
        $builder
            ->add('email')
            ->add('password', PasswordType::class, [
                'required' => false
            ])
            ->add('nombre')
            ->add('perfil');
            if ($options['isSuperadmin']) {
                $builder
                ->add('roles', ChoiceType::class, [
                    'choices' => [
                       $choices
                    ],
                    'multiple' => true,
                    'expanded' => true
                ]);
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'isSuperadmin' => false,
            'data_class' => Usuario::class,
        ]);
    }
}
