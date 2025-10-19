<?php

namespace App\Entity;

use App\Repository\ProcessingunitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProcessingunitRepository::class)]
class Processingunit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'processingunits')]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $hardware = null;

    #[ORM\Column]
    private ?int $taskscompleted = null;

    #[ORM\Column]
    private ?int $reliability = null;

    #[ORM\Column(length: 255)]
    private ?string $securitytoken = null;

    /**
     * @var Collection<int, Genrequest>
     */
    #[ORM\OneToMany(targetEntity: Genrequest::class, mappedBy: 'processingunit')]
    private Collection $genrequests;

    /**
     * @var Collection<int, File>
     */
    #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'processingunit')]
    private Collection $files;

    public function __construct()
    {
        $this->genrequests = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

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

    public function getHardware(): ?string
    {
        return $this->hardware;
    }

    public function setHardware(string $hardware): static
    {
        $this->hardware = $hardware;

        return $this;
    }

    public function getTaskscompleted(): ?int
    {
        return $this->taskscompleted;
    }

    public function setTaskscompleted(int $taskscompleted): static
    {
        $this->taskscompleted = $taskscompleted;

        return $this;
    }

    public function getReliability(): ?int
    {
        return $this->reliability;
    }

    public function setReliability(int $reliability): static
    {
        $this->reliability = $reliability;

        return $this;
    }

    public function getSecuritytoken(): ?string
    {
        return $this->securitytoken;
    }

    public function setSecuritytoken(string $securitytoken): static
    {
        $this->securitytoken = $securitytoken;

        return $this;
    }

    /**
     * @return Collection<int, Genrequest>
     */
    public function getGenrequests(): Collection
    {
        return $this->genrequests;
    }

    public function addGenrequest(Genrequest $genrequest): static
    {
        if (!$this->genrequests->contains($genrequest)) {
            $this->genrequests->add($genrequest);
            $genrequest->setProcessingunit($this);
        }

        return $this;
    }

    public function removeGenrequest(Genrequest $genrequest): static
    {
        if ($this->genrequests->removeElement($genrequest)) {
            // set the owning side to null (unless already changed)
            if ($genrequest->getProcessingunit() === $this) {
                $genrequest->setProcessingunit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, File>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setProcessingunit($this);
        }

        return $this;
    }

    public function removeFile(File $file): static
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getProcessingunit() === $this) {
                $file->setProcessingunit(null);
            }
        }

        return $this;
    }
}
