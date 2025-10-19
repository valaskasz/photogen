<?php

namespace App\Entity;

use App\Repository\GenrequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenrequestRepository::class)]
class Genrequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'genrequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTime $dcreated = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $promtpositive = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $promtnegative = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $startprocessing = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $endprocessing = null;

    #[ORM\Column(nullable: true)]
    private ?bool $refused = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $refusereason = null;

    #[ORM\Column]
    private ?int $priority = null;

    #[ORM\Column(length: 255)]
    private ?string $resolution = null;

    #[ORM\Column(length: 255)]
    private ?string $modelname = null;

    #[ORM\ManyToOne(inversedBy: 'genrequests')]
    private ?Processingunit $processingunit = null;

    /**
     * @var Collection<int, Genfiles>
     */
    #[ORM\OneToMany(targetEntity: Genfiles::class, mappedBy: 'genrequest')]
    private Collection $genfiles;

    #[ORM\ManyToOne(inversedBy: 'genrequests')]
    private ?File $inputfile = null;

    #[ORM\Column(nullable: true)]
    private ?bool $useinputfile = null;

    public function __construct()
    {
        $this->genfiles = new ArrayCollection();
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

    public function getDcreated(): ?\DateTime
    {
        return $this->dcreated;
    }

    public function setDcreated(\DateTime $dcreated): static
    {
        $this->dcreated = $dcreated;

        return $this;
    }

    public function getPromtpositive(): ?string
    {
        return $this->promtpositive;
    }

    public function setPromtpositive(?string $promtpositive): static
    {
        $this->promtpositive = $promtpositive;

        return $this;
    }

    public function getPromtnegative(): ?string
    {
        return $this->promtnegative;
    }

    public function setPromtnegative(?string $promtnegative): static
    {
        $this->promtnegative = $promtnegative;

        return $this;
    }

    public function getStartprocessing(): ?\DateTime
    {
        return $this->startprocessing;
    }

    public function setStartprocessing(?\DateTime $startprocessing): static
    {
        $this->startprocessing = $startprocessing;

        return $this;
    }

    public function getEndprocessing(): ?\DateTime
    {
        return $this->endprocessing;
    }

    public function setEndprocessing(?\DateTime $endprocessing): static
    {
        $this->endprocessing = $endprocessing;

        return $this;
    }

    public function isRefused(): ?bool
    {
        return $this->refused;
    }

    public function setRefused(?bool $refused): static
    {
        $this->refused = $refused;

        return $this;
    }

    public function getRefusereason(): ?string
    {
        return $this->refusereason;
    }

    public function setRefusereason(?string $refusereason): static
    {
        $this->refusereason = $refusereason;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getResolution(): ?string
    {
        return $this->resolution;
    }

    public function setResolution(string $resolution): static
    {
        $this->resolution = $resolution;

        return $this;
    }

    public function getModelname(): ?string
    {
        return $this->modelname;
    }

    public function setModelname(string $modelname): static
    {
        $this->modelname = $modelname;

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
            $genfile->setGenrequest($this);
        }

        return $this;
    }

    public function removeGenfile(Genfiles $genfile): static
    {
        if ($this->genfiles->removeElement($genfile)) {
            // set the owning side to null (unless already changed)
            if ($genfile->getGenrequest() === $this) {
                $genfile->setGenrequest(null);
            }
        }

        return $this;
    }

    public function getInputfile(): ?File
    {
        return $this->inputfile;
    }

    public function setInputfile(?File $inputfile): static
    {
        $this->inputfile = $inputfile;

        return $this;
    }

    public function isUseinputfile(): ?bool
    {
        return $this->useinputfile;
    }

    public function setUseinputfile(?bool $useinputfile): static
    {
        $this->useinputfile = $useinputfile;

        return $this;
    }

    
}
