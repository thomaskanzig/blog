<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SettingRepository")
 */
class Setting
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
    private $google_gtag_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url_facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url_instagram;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url_linkedin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url_github;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGoogleGtagId(): ?string
    {
        return $this->google_gtag_id;
    }

    public function setGoogleGtagId(?string $google_gtag_id): self
    {
        $this->google_gtag_id = $google_gtag_id;

        return $this;
    }

    public function getUrlFacebook(): ?string
    {
        return $this->url_facebook;
    }

    public function setUrlFacebook(?string $url_facebook): self
    {
        $this->url_facebook = $url_facebook;

        return $this;
    }

    public function getUrlInstagram(): ?string
    {
        return $this->url_instagram;
    }

    public function setUrlInstagram(string $url_instagram): self
    {
        $this->url_instagram = $url_instagram;

        return $this;
    }

    public function getUrlLinkedin(): ?string
    {
        return $this->url_linkedin;
    }

    public function setUrlLinkedin(?string $url_linkedin): self
    {
        $this->url_linkedin = $url_linkedin;

        return $this;
    }

    public function getUrlGithub(): ?string
    {
        return $this->url_github;
    }

    public function setUrlGithub(?string $url_github): self
    {
        $this->url_github = $url_github;

        return $this;
    }
}
