<?php

namespace P5\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ResetMailType extends AbstractType
{
	 public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('resetmail' , TextType::class, array( 'label' => 'Adresse e-mail : '))
                ->add('Valider', SubmitType::class);
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
{
    $resolver->setDefaults(array(
        'data_class' => 'P5\UserBundle\Form\Model\ResetMail',
    ));
}
}