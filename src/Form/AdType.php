<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdType extends AbstractType
{
    private function param($titre, $placeholder,array $option = []){
        return ['attr' => array_merge(['placeholder' => $placeholder],$option),'label' => $titre,];

    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,$this->param('Titre','Entrer un titre pour votre annonce'))
            ->add('coverImage',UrlType::class,[
                'attr' => ['placeholder' => "Indiquer l'Url de l'image de couverture"],
                'label' => 'Image de courverture'
            ])
            ->add('introduction',TextareaType::class,[
                'attr' => ['placeholder' => 'Faite une breve introduction de votre annonce'],
                'label' => 'Introduction'
            ])
            ->add('content',TextareaType::class,[
                'attr' => ['placeholder' => 'Faite une description de votre annonce'],
                'label' => 'Description'
            ])
            ->add('rooms',IntegerType::class,$this->param('nombre de chambre','Indiquer le nombre de chambre',['min'=> '0']))
            ->add('price',MoneyType::class,[
                'attr' => ['placeholder' => 'Rentrer un prix par nuit en euro'],
                'label' => 'Prix'
            ])
            ->add('images',CollectionType::class,
            [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
