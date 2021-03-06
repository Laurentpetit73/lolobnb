<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Service\Pagination;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/booking/{page<\d+>?1}", name="admin_bookings_index")
     */
    public function index( $page , Pagination $pagination)
    {
        $pagination->setEntityClass(Booking::class)
            ->setCurrentPage($page);

        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    /**
     * @Route("/admin/booking/{id}/edit", name="admin_bookings_edit")
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();
            $this->addFlash('success',"La reservation <strong>".$booking->getId()."</strong> a bien été modifié");
            return $this->redirectToRoute('admin_bookings_index');
        }

        return $this->render('admin/booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
            
        ]);
        
    }
    /**
     * @Route("/admin/booking/{id}/delete", name="admin_bookings_delete")
     */
    public function delete(Booking $booking ,EntityManagerInterface $manager)
    {
            $manager->remove($booking);
            $manager->flush();
            $this->addFlash('success',"L'annonce a bien été supprimé");
            return $this->redirectToRoute('admin_bookings_index');
        
    }
}
