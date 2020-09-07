<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType {
    protected function param($titre, $placeholder,array $option = []){
        return ['attr' => array_merge(['placeholder' => $placeholder],$option),'label' => $titre,];

    }
}