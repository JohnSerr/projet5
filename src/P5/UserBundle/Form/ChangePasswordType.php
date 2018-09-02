<?php

namespace P5\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordType extends AbstractType
{
	 public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oldpassword', PasswordType::class)
                ->add('newpassword', RepeatedType::class, array(
                    "type" => PasswordType::class,
                    "invalid_message" => "Les mots de passe doivent correspondre.",
                    "required" => true,
                    "first_options" => array("label" => "Nouveau mot de passe : "),
                    "second_options" => array("label" => "Confirmation du mot de passe : "),
                ))
                ->add('Modifier', SubmitType::class)
        ;
                 
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
{
    $resolver->setDefaults(array(
        'data_class' => 'P5\UserBundle\Form\Model\ChangePassword',
    ));
}
}