<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted("ROLE_USER")
     */
    public function create(Ad $ad=null , Request $request, EntityManagerInterface $manager)
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class,$ad);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);

            }
            $ad->setAuthor($this->getUser());
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash('success',"L'annonce <strong>".$ad->getTitle()."</strong> a bien été ajouté");
            return $this->redirectToRoute('ad_show',['slug' => $ad->getSlug()]);
        }



        return $this->render('ad/new.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
               
        ]);
    }
    /** 
    * @Route("/ads/{slug}/edit", name="ad_edit")
    * @Security("is_granted('ROLE_USER') and user ===  ad.getAuthor()", message="vous n'etes pas l'auteur de cet article")
    */
    public function edit(Ad $ad , Request $request, EntityManagerInterface $manager)
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



        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
               
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
    /** 
    * @Route("/ads/{slug}/delete", name="ad_delete")
    * @Security("is_granted('ROLE_USER') and user ===  ad.getAuthor()", message="vous n'avez pas le droit d'acceder à cette ressource")
    */
    public function delete(Ad $ad , Request $request, EntityManagerInterface $manager)
    {
        $manager->remove($ad);
        $manager->flush();
        $this->addFlash('success',"Votre Annonce <strong>".$ad->getTitle()."</strong> a bien été supprimé");
        return $this->redirectToRoute('account_user');


    }
    
}
