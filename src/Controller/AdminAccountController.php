<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_account_login")
     */
    public function index(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $email = $utils->getLastUsername();

        return $this->render('admin/account/login.html.twig', [
            'haserror' => $error !== null  ,
            'email' => $email   
        ]);
    }
    /**
     * @Route("/admin/logout", name="admin_account_logout")
     */
    public function logout()
    {
      
    }
}
