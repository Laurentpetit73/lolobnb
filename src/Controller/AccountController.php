<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($user,$user->getPassword());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success',"Votre Compte <strong>".$user->getEmail()."</strong> a bien été ajouté");
            return $this->redirectToRoute('account_login');
        }
       
        
        return $this->render('account/register.html.twig', [
            'form' => $form->createView()
             
        ]);
    }
    /**
     * @Route("/account/profil", name="account_profil")
     * @IsGranted("ROLE_USER")
     */
    public function profil(  Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success',"Votre Compte <strong>".$user->getEmail()."</strong> a bien été modifié");
            return $this->redirectToRoute('account_profil');
        }
       
        
        return $this->render('account/profil.html.twig', [
            'form' => $form->createView()
             
        ]);
    }
     /**
     * @Route("/account/update-pass", name="account_pass")
     * @IsGranted("ROLE_USER")
     */
    public function updatePass(  Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();
        $pass = new PasswordUpdate();
        $form = $this->createForm(PasswordUpdateType::class, $pass);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(password_verify($pass->getOldPass(),$user->getHash())){
                $hash = $encoder->encodePassword($user,$pass->getNewPass());
                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success',"Le mot de passe de  <strong>".$user->getEmail()."</strong> a bien été modifié");
                return $this->redirectToRoute('account_profil');
            }else{
                $form->get('oldPass')->addError(new FormError('Votre ancien mot de pass ne correspond pas'));
                
            }
        }
       
        
        return $this->render('account/pass.html.twig', [
            'form' => $form->createView()
             
        ]);
    }
    /**
     * @Route("/account", name="account_user")
     * @IsGranted("ROLE_USER")
     */
    public function myAccount(AuthenticationUtils $utils)
    {
        $user = $this->getUser();
        
        return $this->render('user/index.html.twig', [
            'user' => $user  
        ]);
    }
    
}
