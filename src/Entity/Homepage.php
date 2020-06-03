<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HomepageRepository")
 */
class Homepage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $locale;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $limit_highlights;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sidebar_about_me_photo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sidebar_about_me_text;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $sidebar_categories = [];

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getLimitHighlights(): ?int
    {
        return $this->limit_highlights;
    }

    public function setLimitHighlights(?int $limit_highlights): self
    {
        $this->limit_highlights = $limit_highlights;

        return $this;
    }

    public function getSidebarAboutMePhoto(): ?string
    {
        return $this->sidebar_about_me_photo;
    }

    public function setSidebarAboutMePhoto(?string $sidebar_about_me_photo): self
    {
        $this->sidebar_about_me_photo = $sidebar_about_me_photo;

        return $this;
    }

    public function getSidebarAboutMeText(): ?string
    {
        return $this->sidebar_about_me_text;
    }

    public function setSidebarAboutMeText(?string $sidebar_about_me_text): self
    {
        $this->sidebar_about_me_text = $sidebar_about_me_text;

        return $this;
    }

    public function getSidebarCategories(): ?array
    {
        return $this->sidebar_categories;
    }

    public function setSidebarCategories(?array $sidebar_categories): self
    {
        $this->sidebar_categories = $sidebar_categories;

        return $this;
    }

    public function getModified(): ?\DateTimeInterface
    {
        return $this->modified;
    }

    public function setModified(?\DateTimeInterface $modified): self
    {
        $this->modified = $modified;

        return $this;
    }
}
