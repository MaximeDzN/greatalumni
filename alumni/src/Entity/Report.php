<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReportRepository")
 */
class Report
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $local_erreur;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $alert_level;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comments;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocalErreur(): ?string
    {
        return $this->local_erreur;
    }

    public function setLocalErreur(string $local_erreur): self
    {
        $this->local_erreur = $local_erreur;

        return $this;
    }

    public function getAlertLevel(): ?string
    {
        return $this->alert_level;
    }

    public function setAlertLevel(string $alert_level): self
    {
        $this->alert_level = $alert_level;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
