<?php

namespace App\Entity;

use App\Repository\FileuserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileuserRepository::class)]
class Fileuser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'fileusers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'fileusers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FIle $file = null;

    #[ORM\Column]
    private ?\DateTime $dcreated = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getFile(): ?FIle
    {
        return $this->file;
    }

    public function setFile(?FIle $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getDcreated(): ?\DateTime
    {
        return $this->dcreated;
    }

    public function setDcreated(\DateTime $dcreated): static
    {
        $this->dcreated = $dcreated;

        return $this;
    }
}
