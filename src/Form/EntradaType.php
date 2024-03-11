<?php

namespace App\Form;

use App\Entity\Categoria;
use App\Entity\Entrada;
use App\Entity\Etiqueta;
use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntradaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('slug')
            ->add('titulo')
            ->add('fecha', null, [
                'widget' => 'single_text',
            ])
            ->add('estado')
            ->add('resumen')
            ->add('texto')
            ->add('categoria', EntityType::class, [
                'class' => Categoria::class,
                'choice_label' => 'id',
            ])
            ->add('usuario', EntityType::class, [
                'class' => Usuario::class,
                'choice_label' => 'id',
            ])
            ->add('etiquetas', EntityType::class, [
                'class' => Etiqueta::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entrada::class,
        ]);
    }
}
