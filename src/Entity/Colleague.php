<?php

namespace App\Entity;

use App\Repository\ColleagueRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ColleagueRepository::class)
 * @ORM\Table(name="colleagues")
 * @vich\Uploadable
 */
class Colleague
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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(nullable=true)
     * @Vich\UploadableField(mapping="colleague_image", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @var DateTime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $created_at;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /** @return string|null */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null image
     * @return $this
     */
    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /** @return File|null */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /** @param File|null $imageFile */
    public function setImageFile(?File $imageFile=null)
    {
        $this->imageFile = $imageFile;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->created_at = $createdAt;

        return $this;
    }
}