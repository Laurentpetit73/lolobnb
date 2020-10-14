<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class Stats{

    private $entityClass = [ 'users' => 'App\Entity\User',
                            'ads' =>'App\Entity\Ad',
                            'bookings' =>'App\Entity\Booking',
                            'comments' => 'App\Entity\Comment'];
    private $manager;
    private $result = 5;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        
    }

    public function count($entity){
        return $this->manager->createQuery('SELECT COUNT(u) FROM '.$entity.' u')->getSingleScalarResult();
    }

    public function getStats(){
        $stats = [];
        foreach($this->entityClass as $entity => $path){
            $stats[$entity]= $this->count($path);

        }
        return $stats;
    }

    public function getAdsStats(string $order = 'DESC'){
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c 
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note '.$order)
            ->setMaxResults($this->result)
            ->getResult();

    }



    

    

}
