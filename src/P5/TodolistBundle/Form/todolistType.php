<?php

namespace P5\TodolistBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class todolistType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("title", TextType::class, array(
        "label" => "Titre :"    
        ))
                ->add("content", TextareaType::class, array(
        "label" => "Tâche à effectuer :"     
        ))
                ->add("remind", CheckboxType::class, array(
        "label" => "Programmer un rappel (Une notification vous sera envoyée à la date choisie.)",
        "required" => false
        ))
                ->add("dateofend", DateType::class, array(
        "label" => "Date de rappel :"           
                ))
                ->add("Envoyer", SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'P5\TodolistBundle\Entity\todolist'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'p5_todolistbundle_todolist';
    }


}
