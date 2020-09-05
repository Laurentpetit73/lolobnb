<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($i=1; $i<30;$i++){
        $ad = new Ad();
        $title = $faker->sentence(); 
        $ad->setTitle($title)
            ->setPrice($faker->numberBetween(40,200)) 
            ->setIntroduction('<p>'.$faker->paragraph(2).'</p>')
            ->setContent('<p>'.implode('</p><p>',$faker->paragraphs()).'</p>')
            ->setCoverImage($faker->imageUrl(1000,350))
            ->setRooms($faker->numberBetween(1,5))
            ;
        for($j=1; $j<mt_rand(3,6);$j++){
            $img = new Image();
            $img->setUrl($faker->imageUrl(700,350))
                            ->setCaption($faker->sentence())
                            ->setAd($ad);

            $manager->persist($img);
        }
        
        $manager->persist($ad);
        }

        $manager->flush();
    }
}
