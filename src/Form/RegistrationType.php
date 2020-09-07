<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,$this->param('Email','Veuillez rentrer votre email'))
            ->add('firstName',TextType::class,$this->param('Prénom','Rentrer votre prénom'))
            ->add('lastName',TextType::class,$this->param('Nom','Rentrer votre nom'))
            ->add('hash',PasswordType::class,$this->param('Password','Rentrer votre password'))
            ->add('picture',UrlType::class,$this->param('Avatar',"Rentrer l'url de votre avatar"))
            ->add('introduction',TextareaType::class,$this->param('Introduction','Rentrer une intro'))
            ->add('description',TextareaType::class,$this->param('Description','Rentrer une description'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
