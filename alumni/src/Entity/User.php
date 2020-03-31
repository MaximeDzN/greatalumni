<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $login;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nickname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $department;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $promo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

   /**
     * @ORM\Column(type="integer")
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\News", mappedBy="author")
     */
    private $news;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="Author")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Score", mappedBy="User")
     */
    private $scores;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="UserSend")
     */
    private $SendMessages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="UserReceive")
     */
    private $receiveMessages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="User")
     */
    private $posts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Hobbies", mappedBy="User")
     */
    private $hobbies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostAnswer", mappedBy="User")
     */
    private $postAnswers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Course", mappedBy="User")
     */
    private $courses;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isConfirmed;

    public function __construct()
    {
        $this->news = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->scores = new ArrayCollection();
        $this->SendMessages = new ArrayCollection();
        $this->receiveMessages = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->hobbies = new ArrayCollection();
        $this->postAnswers = new ArrayCollection();
        $this->courses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getPromo(): ?string
    {
        return $this->promo;
    }

    public function setPromo(string $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection|News[]
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

    public function addNews(News $news): self
    {
        if (!$this->news->contains($news)) {
            $this->news[] = $news;
            $news->setAuthor($this);
        }

        return $this;
    }

    public function removeNews(News $news): self
    {
        if ($this->news->contains($news)) {
            $this->news->removeElement($news);
            // set the owning side to null (unless already changed)
            if ($news->getAuthor() === $this) {
                $news->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Score[]
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): self
    {
        if (!$this->scores->contains($score)) {
            $this->scores[] = $score;
            $score->setUser($this);
        }

        return $this;
    }

    public function removeScore(Score $score): self
    {
        if ($this->scores->contains($score)) {
            $this->scores->removeElement($score);
            // set the owning side to null (unless already changed)
            if ($score->getUser() === $this) {
                $score->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getSendMessages(): Collection
    {
        return $this->SendMessages;
    }

    public function addSendMessage(Message $sendMessage): self
    {
        if (!$this->SendMessages->contains($sendMessage)) {
            $this->SendMessages[] = $sendMessage;
            $sendMessage->setUserSend($this);
        }

        return $this;
    }

    public function removeSendMessage(Message $sendMessage): self
    {
        if ($this->SendMessages->contains($sendMessage)) {
            $this->SendMessages->removeElement($sendMessage);
            // set the owning side to null (unless already changed)
            if ($sendMessage->getUserSend() === $this) {
                $sendMessage->setUserSend(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getReceiveMessages(): Collection
    {
        return $this->receiveMessages;
    }

    public function addReceiveMessage(Message $receiveMessage): self
    {
        if (!$this->receiveMessages->contains($receiveMessage)) {
            $this->receiveMessages[] = $receiveMessage;
            $receiveMessage->setUserReceive($this);
        }

        return $this;
    }

    public function removeReceiveMessage(Message $receiveMessage): self
    {
        if ($this->receiveMessages->contains($receiveMessage)) {
            $this->receiveMessages->removeElement($receiveMessage);
            // set the owning side to null (unless already changed)
            if ($receiveMessage->getUserReceive() === $this) {
                $receiveMessage->setUserReceive(null);
            }
        }

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
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Hobbies[]
     */
    public function getHobbies(): Collection
    {
        return $this->hobbies;
    }

    public function addHobby(Hobbies $hobby): self
    {
        if (!$this->hobbies->contains($hobby)) {
            $this->hobbies[] = $hobby;
            $hobby->addUser($this);
        }

        return $this;
    }

    public function removeHobby(Hobbies $hobby): self
    {
        if ($this->hobbies->contains($hobby)) {
            $this->hobbies->removeElement($hobby);
            $hobby->removeUser($this);
        }

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
            $postAnswer->setUser($this);
        }

        return $this;
    }

    public function removePostAnswer(PostAnswer $postAnswer): self
    {
        if ($this->postAnswers->contains($postAnswer)) {
            $this->postAnswers->removeElement($postAnswer);
            // set the owning side to null (unless already changed)
            if ($postAnswer->getUser() === $this) {
                $postAnswer->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->addUser($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->contains($course)) {
            $this->courses->removeElement($course);
            $course->removeUser($this);
        }

        return $this;
    }

    public function getIsConfirmed(): ?bool
    {
        return $this->isConfirmed;
    }

    public function setIsConfirmed(bool $isConfirmed): self
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }
}
