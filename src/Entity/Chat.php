<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatRepository")
 */
class Chat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="chats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $participantA;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="chats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $participantB;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ChatMessage", mappedBy="Chat", orphanRemoval=true)
     */
    private $chatMessages;

    public function __construct()
    {
        $this->chatMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParticipantA(): ?User
    {
        return $this->participantA;
    }

    public function setParticipantA(?User $participantA): self
    {
        $this->participantA = $participantA;

        return $this;
    }

    public function getParticipantB(): ?User
    {
        return $this->participantB;
    }

    public function setParticipantB(?User $participantB): self
    {
        $this->participantB = $participantB;

        return $this;
    }

    /**
     * @return Collection|ChatMessage[]
     */
    public function getChatMessages(): Collection
    {
        return $this->chatMessages;
    }

    public function addChatMessage(ChatMessage $chatMessage): self
    {
        if (!$this->chatMessages->contains($chatMessage)) {
            $this->chatMessages[] = $chatMessage;
            $chatMessage->setChat($this);
        }

        return $this;
    }

    public function removeChatMessage(ChatMessage $chatMessage): self
    {
        if ($this->chatMessages->contains($chatMessage)) {
            $this->chatMessages->removeElement($chatMessage);
            // set the owning side to null (unless already changed)
            if ($chatMessage->getChat() === $this) {
                $chatMessage->setChat(null);
            }
        }

        return $this;
    }
}
