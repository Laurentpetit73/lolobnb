<?php

namespace App\Entity;

use App\Repository\PasswordUpdateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


class PasswordUpdate
{

    private $oldPass;

    /**
     * @Assert\Length(min=8,max=255,minMessage = "Votre mot de pass doit faire au moins {{ limit }} characters")
     */

    private $newPass;

    /**
     * @Assert\EqualTo(propertyPath="newPass",message = "Vous n'avez pas correctement confirmer votre mot de pass" )
     */

    private $confirmPass;

    public function getOldPass(): ?string
    {
        return $this->oldPass;
    }

    public function setOldPass(string $oldPass): self
    {
        $this->oldPass = $oldPass;

        return $this;
    }

    public function getNewPass(): ?string
    {
        return $this->newPass;
    }

    public function setNewPass(string $newPass): self
    {
        $this->newPass = $newPass;

        return $this;
    }

    public function getConfirmPass(): ?string
    {
        return $this->confirmPass;
    }

    public function setConfirmPass(string $confirmPass): self
    {
        $this->confirmPass = $confirmPass;

        return $this;
    }
}
