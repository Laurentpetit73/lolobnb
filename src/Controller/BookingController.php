<?php

namespace App\Controller;

use DateTime;
use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_new")
     * @IsGranted("ROLE_USER")
     */
    public function book(Ad $ad, Request $request, EntityManagerInterface $manager )
    {
        $booking = new Booking();
        $booker = $this->getUser();
        $form = $this->createForm(BookingType::class,$booking);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $booking->setBooker($booker)
                    ->setAd($ad);

            $manager->persist($booking);
            $manager->flush();

            return $this->redirectToRoute('booking_show',['id' => $booking->getId()]);
             
        }
        return $this->render('booking/book.html.twig', [
            'controller_name' => 'BookingController',
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }
    /**
     * @Route("/booking/{id}", name="booking_show")
     * @IsGranted("ROLE_USER")
     */
    public function show(Booking $booking )
    {
       
        return $this->render('booking/show.html.twig', [
            'booking' => $booking
        ]);
    }
}
