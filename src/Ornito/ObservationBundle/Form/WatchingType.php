<?php

namespace Ornito\ObservationBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class WatchingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class, array(
                'required' => false
            ))
            ->add('latitude', NumberType::class)
            ->add('longitude', NumberType::class)
            ->add('title', TextType::class)
            ->add('image', ImageType::class, array(
                'required' => false
            ))
            ->add('species', EntityType::class, array(
                'class'         => 'OrnitoTaxrefBundle:Species',
                'choice_label' => 'scientificName',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ornito\ObservationBundle\Entity\Watching'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ornito_observationbundle_watching';
    }


}
