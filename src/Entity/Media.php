<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 */
class Media
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $folder_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Folder")
     * @ORM\JoinColumn(nullable=true)
     */
    private $folder;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" : 0})
     */
    private $external;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MediaType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MediaData", mappedBy="media")
     */
    private $mediaData;

    public function __construct()
    {
        $this->mediaData = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?\DateTimeInterface $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getFolder(): ?Folder
    {
        return $this->folder;
    }

    public function setFolder(?Folder $folder): self
    {
        $this->folder = $folder;

        return $this;
    }

    public function getFolderId(): ?int
    {
        return $this->folder_id;
    }

    public function setFolderId(?string $folder_id): self
    {
        $this->folder_id = $folder_id;

        return $this;
    }

    public function getExternal(): ?bool
    {
        return $this->external;
    }

    public function setExternal(bool $external): self
    {
        $this->external = $external;

        return $this;
    }

    public function getType(): ?MediaType
    {
        return $this->type;
    }

    public function setType(?MediaType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|MediaData[]
     */
    public function getMediaData(): Collection
    {
        return $this->mediaData;
    }

    public function addMediaData(MediaData $mediaData): self
    {
        if (!$this->mediaData->contains($mediaData)) {
            $this->mediaData[] = $mediaData;
            $mediaData->setMedia($this);
        }

        return $this;
    }

    public function removeMediaData(MediaData $mediaData): self
    {
        if ($this->mediaData->contains($mediaData)) {
            $this->mediaData->removeElement($mediaData);
            // set the owning side to null (unless already changed)
            if ($mediaData->getMedia() === $this) {
                $mediaData->setMedia(null);
            }
        }

        return $this;
    }
}
