<?php

namespace App\Controller;

use DateTime;
use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
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

            if(!$booking->isBookableDates()){
                $this->addFlash('warning',"Ces dates sont déjà prise");
            }else{

            $manager->persist($booking);
            $manager->flush();

            return $this->redirectToRoute('booking_show',['id' => $booking->getId(),'withalert'=> 'true'] );
            }
             
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
    public function show(Booking $booking , Request $request, EntityManagerInterface $manager)
    {
        $comment = new Comment();
        $booker = $this->getUser();
        $form = $this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $comment->setAuthor($booker)
                    ->setAd($booking->getAd());

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success','votre commentaire a bien été pris en compte');
        }
             
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }
}
