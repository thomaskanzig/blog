<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * @Gedmo\Timestampable(on="update")
     */
    private $modified;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sidebar_about_me_url_facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sidebar_about_me_url_instagram;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sidebar_about_me_url_github;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sidebar_about_me_url_youtube;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sidebar_about_me_url_linkedin;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $sidebar_about_me_active = false;

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

    public function getSidebarAboutMeUrlFacebook(): ?string
    {
        return $this->sidebar_about_me_url_facebook;
    }

    public function setSidebarAboutMeUrlFacebook(?string $sidebar_about_me_url_facebook): self
    {
        $this->sidebar_about_me_url_facebook = $sidebar_about_me_url_facebook;

        return $this;
    }

    public function getSidebarAboutMeUrlInstagram(): ?string
    {
        return $this->sidebar_about_me_url_instagram;
    }

    public function setSidebarAboutMeUrlInstagram(?string $sidebar_about_me_url_instagram): self
    {
        $this->sidebar_about_me_url_instagram = $sidebar_about_me_url_instagram;

        return $this;
    }

    public function getSidebarAboutMeUrlGithub(): ?string
    {
        return $this->sidebar_about_me_url_github;
    }

    public function setSidebarAboutMeUrlGithub(?string $sidebar_about_me_url_github): self
    {
        $this->sidebar_about_me_url_github = $sidebar_about_me_url_github;

        return $this;
    }

    public function getSidebarAboutMeUrlYoutube(): ?string
    {
        return $this->sidebar_about_me_url_youtube;
    }

    public function setSidebarAboutMeUrlYoutube(?string $sidebar_about_me_url_youtube): self
    {
        $this->sidebar_about_me_url_youtube = $sidebar_about_me_url_youtube;

        return $this;
    }

    public function getSidebarAboutMeUrlLinkedin(): ?string
    {
        return $this->sidebar_about_me_url_linkedin;
    }

    public function setSidebarAboutMeUrlLinkedin(?string $sidebar_about_me_url_linkedin): self
    {
        $this->sidebar_about_me_url_linkedin = $sidebar_about_me_url_linkedin;

        return $this;
    }

    public function getSidebarAboutMeActive(): ?bool
    {
        return $this->sidebar_about_me_active;
    }

    public function setSidebarAboutMeActive(?bool $sidebar_about_me_active): self
    {
        $this->sidebar_about_me_active = $sidebar_about_me_active;

        return $this;
    }
}
