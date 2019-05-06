<?php
// src/Entity/User.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="createdBy")
     */
    private $comments;

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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Video", mappedBy="AddedBy")
     */
    private $videosAdded;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Video", mappedBy="usersAddedNotation")
     */
    private $videos;


    public function __construct()
    {
        parent::__construct();
        $this->comments = new ArrayCollection();
        $this->videosAdded = new ArrayCollection();
        $this->videos = new ArrayCollection();
        // your own logic
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
            $comment->setCreatedBy($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getCreatedBy() === $this) {
                $comment->setCreatedBy(null);
            }
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
     * @return Collection|Video[]
     */
    public function getVideosAdded(): Collection
    {
        return $this->videosAdded;
    }

    public function addVideosAdded(Video $videosAdded): self
    {
        if (!$this->videosAdded->contains($videosAdded)) {
            $this->videosAdded[] = $videosAdded;
            $videosAdded->setAddedBy($this);
        }

        return $this;
    }

    public function removeVideosAdded(Video $videosAdded): self
    {
        if ($this->videosAdded->contains($videosAdded)) {
            $this->videosAdded->removeElement($videosAdded);
            // set the owning side to null (unless already changed)
            if ($videosAdded->getAddedBy() === $this) {
                $videosAdded->setAddedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->addUsersAddedNotation($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            $video->removeUsersAddedNotation($this);
        }

        return $this;
    }

}