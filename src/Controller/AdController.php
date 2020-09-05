<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("/ads/new", name="ad_new")
     */
    public function create(Ad $ad=null)
    {
        if(!$ad){
            $ad = new Ad();
        }

        $form = $this->createForm(AdType::class,$ad);

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView(),
               
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
