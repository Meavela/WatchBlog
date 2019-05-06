<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 */
class Video
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeVideo", inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TagVideo", inversedBy="videos")
     */
    private $tags;

    /**
     * @ORM\Column(type="string")
     */
    private $image;

    /**
     * Contiendra l'objet image du formulaire
     * @Assert\Image()
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=2048)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="createdFor")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="videosAdded")
     * @ORM\JoinColumn(nullable=false)
     */
    private $AddedBy;

    /**
     * @ORM\Column(type="integer")
     */
    private $notation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="videos")
     */
    private $usersAddedNotation;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->usersAddedNotation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?TypeVideo
    {
        return $this->type;
    }

    public function setType(?TypeVideo $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|TagVideo[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(TagVideo $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(TagVideo $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile($imageFile): self
    {
        $this->imageFile = $imageFile;

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
            $comment->setCreatedFor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getCreatedFor() === $this) {
                $comment->setCreatedFor(null);
            }
        }

        return $this;
    }

    public function getAddedBy(): ?User
    {
        return $this->AddedBy;
    }

    public function setAddedBy(?User $AddedBy): self
    {
        $this->AddedBy = $AddedBy;

        return $this;
    }

    public function getNotation(): ?int
    {
        return $this->notation;
    }

    public function setNotation(int $notation): self
    {
        $this->notation = $notation;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersAddedNotation(): Collection
    {
        return $this->usersAddedNotation;
    }

    public function addUsersAddedNotation(User $usersAddedNotation): self
    {
        if (!$this->usersAddedNotation->contains($usersAddedNotation)) {
            $this->usersAddedNotation[] = $usersAddedNotation;
        }

        return $this;
    }

    public function removeUsersAddedNotation(User $usersAddedNotation): self
    {
        if ($this->usersAddedNotation->contains($usersAddedNotation)) {
            $this->usersAddedNotation->removeElement($usersAddedNotation);
        }

        return $this;
    }

}
