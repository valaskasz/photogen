<?php

namespace App\Entity;

use App\Repository\FilerequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilerequestRepository::class)]
class Filerequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?File $file = null;

    #[ORM\Column]
    private ?bool $tostore = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $finished = null;

    #[ORM\Column]
    private ?\DateTime $dcreated = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dfinished = null;

    #[ORM\Column(nullable: true)]
    private ?bool $started = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function isTostore(): ?bool
    {
        return $this->tostore;
    }

    public function setTostore(bool $tostore): static
    {
        $this->tostore = $tostore;

        return $this;
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

    public function isFinished(): ?bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished): static
    {
        $this->finished = $finished;

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

    public function getDfinished(): ?\DateTime
    {
        return $this->dfinished;
    }

    public function setDfinished(?\DateTime $dfinished): static
    {
        $this->dfinished = $dfinished;

        return $this;
    }

    public function isStarted(): ?bool
    {
        return $this->started;
    }

    public function setStarted(?bool $started): static
    {
        $this->started = $started;

        return $this;
    }
}
