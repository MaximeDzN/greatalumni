<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JobsRepository")
 */
class Jobs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $YearStart;

    /**
     * @ORM\Column(type="date")
     */
    private $YearStop;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYearStart(): ?\DateTimeInterface
    {
        return $this->YearStart;
    }

    public function setYearStart(\DateTimeInterface $YearStart): self
    {
        $this->YearStart = $YearStart;

        return $this;
    }

    public function getYearStop(): ?\DateTimeInterface
    {
        return $this->YearStop;
    }

    public function setYearStop(\DateTimeInterface $YearStop): self
    {
        $this->YearStop = $YearStop;

        return $this;
    }
}
