<?php

namespace App\DataFixtures;

use DateInterval;
use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;  
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminuser = new User();
            $hash = $this->encoder->encodePassword($adminuser,'Laurent73');
            $adminuser->setFirstName('laurent')
                ->setLastName('petit')
                ->setEmail('petit.laurent@gmail.com')
                ->setPicture('https://randomuser.me/api/portraits/men/'.mt_rand(1,80).'.jpg')
                ->setHash($hash)
                ->setIntroduction('<p>'.$faker->paragraph(2).'</p>')
                ->setDescription('<p>'.implode('</p><p>',$faker->paragraphs(2)).'</p>')
                ->addUserRole($adminRole);

            $manager->persist($adminuser);

        $users=[];
        $genres=['male','female'];

        for($i=0 ; $i<=10 ; $i++){
            $user = new User();
            $hash = $this->encoder->encodePassword($user,'password');
            $genre = $faker->randomElement($genres);
            $genrenname = $genre == 'male' ? 'men' : 'women';

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setPicture('https://randomuser.me/api/portraits/'.$genrenname.'/'.mt_rand(1,80).'.jpg')
                ->setHash($hash)
                ->setIntroduction('<p>'.$faker->paragraph(2).'</p>')
                ->setDescription('<p>'.implode('</p><p>',$faker->paragraphs(2)).'</p>');
            $users[]= $user;
            $manager->persist($user);
            }

        for($k=1 ; $k<=30 ;$k++){
        $ad = new Ad();
        $title = $faker->sentence(); 
        $user = $users[mt_rand(0, count($users)-1)];


        $ad->setTitle($title)
            ->setPrice($faker->numberBetween(40,200)) 
            ->setIntroduction('<p>'.$faker->paragraph(2).'</p>')
            ->setContent('<p>'.implode('</p><p>',$faker->paragraphs()).'</p>')
            ->setCoverImage($faker->imageUrl(1000,350))
            ->setRooms($faker->numberBetween(1,5))
            ->setAuthor($user)
            ;
        for($j=1; $j<mt_rand(3,6);$j++){
            $img = new Image();
            $img->setUrl($faker->imageUrl(700,350))
                            ->setCaption($faker->sentence())
                            ->setAd($ad);

            $manager->persist($img);
        }
        // gestion des resvation

        for($l=1 ;$l <= mt_rand(0,10) ; $l++){
            $booking = new Booking();
            $user2 = $users[mt_rand(0, count($users)-1)];
            $ca=$faker->dateTimeBetween('-1 years', 'now');
            $sd=$faker->dateTimeBetween('now', '+1 years');  
            $add = mt_rand(2,15);
            $se=(clone $sd)->modify("+$add days");
            
            $booking->setBooker($user2)
                    ->setAd($ad)
                    ->setStartDate($sd)
                    ->setEndDate($se)
                    ->setCreatedAt($ca)
                    ->setamount($ad->getPrice()*$add)
                    ->setComment($faker->paragraph());
            
            $manager->persist($booking);
            
        }
        
        $manager->persist($ad);
        }

        

        $manager->flush();
    }
}
