<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\ManyToOne(inversedBy: 'files')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Processingunit $processingunit = null;

    #[ORM\Column(length: 255)]
    private ?string $directory = null;

    /**
     * @var Collection<int, Genfiles>
     */
    #[ORM\OneToMany(targetEntity: Genfiles::class, mappedBy: 'file')]
    private Collection $genfiles;

    /**
     * @var Collection<int, Fileuser>
     */
    #[ORM\OneToMany(targetEntity: Fileuser::class, mappedBy: 'file')]
    private Collection $fileusers;

    /**
     * @var Collection<int, Genrequest>
     */
    #[ORM\OneToMany(targetEntity: Genrequest::class, mappedBy: 'inputfile')]
    private Collection $genrequests;

    public function __construct()
    {
        $this->genfiles = new ArrayCollection();
        $this->fileusers = new ArrayCollection();
        $this->genrequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getProcessingunit(): ?Processingunit
    {
        return $this->processingunit;
    }

    public function setProcessingunit(?Processingunit $processingunit): static
    {
        $this->processingunit = $processingunit;

        return $this;
    }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function setDirectory(string $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * @return Collection<int, Genfiles>
     */
    public function getGenfiles(): Collection
    {
        return $this->genfiles;
    }

    public function addGenfile(Genfiles $genfile): static
    {
        if (!$this->genfiles->contains($genfile)) {
            $this->genfiles->add($genfile);
            $genfile->setFile($this);
        }

        return $this;
    }

    public function removeGenfile(Genfiles $genfile): static
    {
        if ($this->genfiles->removeElement($genfile)) {
            // set the owning side to null (unless already changed)
            if ($genfile->getFile() === $this) {
                $genfile->setFile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fileuser>
     */
    public function getFileusers(): Collection
    {
        return $this->fileusers;
    }

    public function addFileuser(Fileuser $fileuser): static
    {
        if (!$this->fileusers->contains($fileuser)) {
            $this->fileusers->add($fileuser);
            $fileuser->setFile($this);
        }

        return $this;
    }

    public function removeFileuser(Fileuser $fileuser): static
    {
        if ($this->fileusers->removeElement($fileuser)) {
            // set the owning side to null (unless already changed)
            if ($fileuser->getFile() === $this) {
                $fileuser->setFile(null);
            }
        }

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
            $genrequest->setInputfile($this);
        }

        return $this;
    }

    public function removeGenrequest(Genrequest $genrequest): static
    {
        if ($this->genrequests->removeElement($genrequest)) {
            // set the owning side to null (unless already changed)
            if ($genrequest->getInputfile() === $this) {
                $genrequest->setInputfile(null);
            }
        }

        return $this;
    }
}
