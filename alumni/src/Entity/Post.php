<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $Content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PostType", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $PostType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostAnswer", mappedBy="Post")
     */
    private $postAnswers;

    public function __construct()
    {
        $this->postAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(string $Content): self
    {
        $this->Content = $Content;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getPostType(): ?PostType
    {
        return $this->PostType;
    }

    public function setPostType(?PostType $PostType): self
    {
        $this->PostType = $PostType;

        return $this;
    }

    /**
     * @return Collection|PostAnswer[]
     */
    public function getPostAnswers(): Collection
    {
        return $this->postAnswers;
    }

    public function addPostAnswer(PostAnswer $postAnswer): self
    {
        if (!$this->postAnswers->contains($postAnswer)) {
            $this->postAnswers[] = $postAnswer;
            $postAnswer->setPost($this);
        }

        return $this;
    }

    public function removePostAnswer(PostAnswer $postAnswer): self
    {
        if ($this->postAnswers->contains($postAnswer)) {
            $this->postAnswers->removeElement($postAnswer);
            // set the owning side to null (unless already changed)
            if ($postAnswer->getPost() === $this) {
                $postAnswer->setPost(null);
            }
        }

        return $this;
    }
}
