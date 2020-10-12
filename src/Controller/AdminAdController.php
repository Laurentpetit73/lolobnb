<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads", name="admin_ads_index")
     */
    public function index(AdRepository $repo)
    {
        return $this->render('admin/ad/index.html.twig', [
            'ads' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/admin/ads/{slug}/edit/", name="admin_ad_edit")
     */
    public function edit(Ad $ad ,  Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdType::class,$ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);

            }
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash('success',"L'annonce <strong>".$ad->getTitle()."</strong> a bien été modifié");
            return $this->redirectToRoute('ad_show',['slug' => $ad->getSlug()]);
        }


        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/ads/{id}/delete/", name="admin_ad_delete")
     */
    public function delete(Ad $ad , EntityManagerInterface $manager)
    {
        if(count($ad->getBookings())>0){
            $this->addFlash('danger',"L'annonce <strong>".$ad->getTitle()."</strong> possede des reservations");
            return $this->redirectToRoute('admin_ads_index');   
            
        }else{
            $manager->remove($ad);
            $manager->flush();
            $this->addFlash('success',"L'annonce <strong>".$ad->getTitle()."</strong> a bien été supprimeé");
            return $this->redirectToRoute('admin_ads_index');              
        }
        
    }
}
