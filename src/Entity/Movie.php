<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $studios = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $producers = null;

    #[ORM\Column(nullable: true)]
    private ?bool $winner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getStudios(): ?string
    {
        return $this->studios;
    }

    public function setStudios(string $studios): static
    {
        $this->studios = $studios;

        return $this;
    }

    public function getProducers(): ?string
    {
        return $this->producers;
    }

    public function setProducers(string $producers): static
    {
        $this->producers = $producers;

        return $this;
    }

    public function isWinner(): ?bool
    {
        return $this->winner;
    }

    public function setWinner($winner): static
    {
        $this->winner = $winner != '';

        return $this;
    }
}
