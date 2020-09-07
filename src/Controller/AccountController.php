<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $email = $utils->getLastUsername();
        
        return $this->render('account/login.html.twig', [
            'haserror' => $error !== null  ,
            'email' => $email   
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout()
    {
      
    }
    /**
     * @Route("/register", name="account_register")
     */
    public function register(Request $request, EntityManagerInterface $manager)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success',"Votre Compte <strong>".$user->getEmail()."</strong> a bien été ajouté");
            return $this->redirectToRoute('home');
        }
       
        
        return $this->render('account/register.html.twig', [
            'form' => $form->createView()
             
        ]);
    }
}
