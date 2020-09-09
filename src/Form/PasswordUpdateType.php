<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPass',PasswordType::class,$this->param('Ancien Email','Veuillez rentrer votre ancien mot de passe'))
            ->add('newPass',PasswordType::class,$this->param('Ancien Email','Veuillez rentrer votre nouveau mot de passe'))
            ->add('confirmPass',PasswordType::class,$this->param('Ancien Email','Veuillez confirmer votre nouveau mot de passe'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
