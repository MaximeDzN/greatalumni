<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostTypeRepository")
 * @ORM\Table(name="post_type")
 * @UniqueEntity(fields={"title"}, message="Une catégorie existe déjà avec ce nom.")
 */
class PostType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /** 
     * @ORM\Column(type="string", length=191)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $description;

    /** 
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="PostType", orphanRemoval=true)
     */
    private $posts;

    /**
     * @var string
     * @ORM\Column(type="string", length=191)
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setPostType($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getPostType() === $this) {
                $post->setPostType(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }


   
    public function setSlug(string $slug): PostType
    {
        $this->slug = $slug;
        return $this;
    }

    
    public function getSlug(): string
    {
        return $this->slug;
    }
}
