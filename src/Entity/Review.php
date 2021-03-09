<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("movies_read_item")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups("movies_read_item")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("movies_read_item")
     */
    private $publishedAt;

    /**
     * Cette propriété $movie est une relation ManyToOne vers l'entité cible Movie
     * 
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="reviews")
     */
    private $movie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}
