<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(Ad $ad=null , Request $request, EntityManagerInterface $manager)
    {
        if(!$ad){
            $ad = new Ad();
        }

        $form = $this->createForm(AdType::class,$ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);

            }
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash('success',"L'annonce <strong>".$ad->getTitle()."</strong> a bien été ajouté");
            return $this->redirectToRoute('ad_show',['slug' => $ad->getSlug()]);
        }



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
