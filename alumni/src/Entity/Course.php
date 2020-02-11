<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CourseRepository")
 */
class Course
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $naÃme;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etablishment;

    /**
     * @ORM\Column(type="date")
     */
    private $YearStart;

    /**
     * @ORM\Column(type="date")
     */
    private $yearStop;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="courses")
     */
    private $User;

    public function __construct()
    {
        $this->User = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNaÃme(): ?string
    {
        return $this->naÃme;
    }

    public function setNaÃme(string $naÃme): self
    {
        $this->naÃme = $naÃme;

        return $this;
    }

    public function getEtablishment(): ?string
    {
        return $this->etablishment;
    }

    public function setEtablishment(string $etablishment): self
    {
        $this->etablishment = $etablishment;

        return $this;
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
        return $this->yearStop;
    }

    public function setYearStop(\DateTimeInterface $yearStop): self
    {
        $this->yearStop = $yearStop;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->User;
    }

    public function addUser(User $user): self
    {
        if (!$this->User->contains($user)) {
            $this->User[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->User->contains($user)) {
            $this->User->removeElement($user);
        }

        return $this;
    }
}
