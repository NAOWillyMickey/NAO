<?php

namespace Ornito\ObservationBundle\Form;

use Ornito\ObservationBundle\Entity\Watching;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class WatchingValidateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('description')
            ->remove('latitude')
            ->remove('longitude')
            ->remove('title')
            ->remove('image')
            ->remove('species')
            ->add('validateStatus', ChoiceType::class, array(
                'choices' => array(
                    'Valider' => Watching::ATTESTED,
                    'Refuser' => Watching::REJECTED,
                ),
                'expanded' => true,
                'multiple' => false,
            ))
        ;
    }

    public function getParent()
    {
        return WatchingType::class;
    }
}