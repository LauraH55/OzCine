<?php

namespace App\Entity;

use App\Repository\CastingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CastingRepository::class)
 */
class Casting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("movies_read_item")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("movies_read_item")
     */
    private $role;

    /**
     * @ORM\Column(type="integer")
     * @Groups("movies_read_item")
     */
    private $creditOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="castings")
     * @Groups("movies_read_item")
     */
    private $person;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="castings")
     */
    private $movie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getCreditOrder(): ?int
    {
        return $this->creditOrder;
    }

    public function setCreditOrder(int $creditOrder): self
    {
        $this->creditOrder = $creditOrder;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

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
