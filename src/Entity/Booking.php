<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 * @ORM\HasLifecycleCallbacks 
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     * @Assert\GreaterThan("today UTC",message="La date d'arrivé doit être superieur à celle de départ")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     * @Assert\GreaterThan(propertyPath="startDate", message="La date départ doit être superieur à celle d'arrivée")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist(){
        if(empty($this->createdAt)){
            $this->createdAt= new DateTime();
        }
        if(empty($this->amount)){
            
            $this->amount = $this->ad->getPrice()*$this->getDuration() ;
        }

    }
    public function getDuration(){
        $diff = $this->endDate->diff($this->startDate);
        return  $diff->days;
    }

    public function isBookableDates(){

        $notavailableday = $this->ad->getNotAvailableDays();
        $bookingdays = $this->getCurrentDate();

        $formatday = function($day){return $day->format('Y-m-D');};

        $notavailable = array_map($formatday,$notavailableday);
        $booking = array_map($formatday,$bookingdays);

        foreach($booking as $test){
            if(array_search($test, $notavailable)==! false ){
                return false;
            }
        }
        return true;

    }
    public function getCurrentDate(){
        $days = [];
        $resultat = range($this->getStartDate()->getTimestamp(),$this->getEndDate()->getTimestamp(),24*60*60);
        $days = array_map(function($dayTimestamp){
            return new DateTime(date('Y-m-d',$dayTimestamp));
        },$resultat);

        return $days;


    }
}
