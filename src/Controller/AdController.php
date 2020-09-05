<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();
        return $this->render('ad/index.html.twig', [
            'controller_name' => 'AdController',
            'ads' => $ads,
        ]);
    }
    /**
     * @Route("/ads/{slug}", name="ad_show")
     */
    public function show(Ad $ad)
    {
        return $this->render('ad/show.html.twig', [
            'controller_name' => 'AdController',
            'ad' => $ad    
        ]);
    }
}
