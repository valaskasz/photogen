<?php

namespace App\Entity;

use App\Repository\GenfilesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenfilesRepository::class)]
class Genfiles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'genfiles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Genrequest $genrequest = null;

    #[ORM\ManyToOne(inversedBy: 'genfiles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?File $file = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGenrequest(): ?Genrequest
    {
        return $this->genrequest;
    }

    public function setGenrequest(?Genrequest $genrequest): static
    {
        $this->genrequest = $genrequest;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): static
    {
        $this->file = $file;

        return $this;
    }
}
