<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sendDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="SendMessages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserSend;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="receiveMessages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserReceive;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSendDate(): ?\DateTimeInterface
    {
        return $this->sendDate;
    }

    public function setSendDate(\DateTimeInterface $sendDate): self
    {
        $this->sendDate = $sendDate;

        return $this;
    }

    public function getUserSend(): ?User
    {
        return $this->UserSend;
    }

    public function setUserSend(?User $UserSend): self
    {
        $this->UserSend = $UserSend;

        return $this;
    }

    public function getUserReceive(): ?User
    {
        return $this->UserReceive;
    }

    public function setUserReceive(?User $UserReceive): self
    {
        $this->UserReceive = $UserReceive;

        return $this;
    }
}
