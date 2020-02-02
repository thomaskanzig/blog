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

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $app_id_facebook;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $show_comments_facebook = false;

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

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getAppIdFacebook(): ?string
    {
        return $this->app_id_facebook;
    }

    public function setAppIdFacebook(?string $app_id_facebook): self
    {
        $this->app_id_facebook = $app_id_facebook;

        return $this;
    }

    public function getShowCommentsFacebook(): ?bool
    {
        return $this->show_comments_facebook;
    }

    public function setShowCommentsFacebook(?bool $show_comments_facebook): self
    {
        $this->show_comments_facebook = $show_comments_facebook;

        return $this;
    }
}
