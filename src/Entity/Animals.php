<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\SlugTrait;
use App\Repository\AnimalsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: AnimalsRepository::class)]
class Animals
{

    use CreatedAtTrait;
    use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Races $races = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sex $sex = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $status = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    private ?Refuges $refuge = null;

    #[ORM\OneToMany(mappedBy: 'animals', targetEntity: Images::class, orphanRemoval: true)]
    private Collection $images;

    #[ORM\OneToOne(mappedBy: 'animal', cascade: ['persist', 'remove'])]
    private ?Adoption $adoption = null;

    #[ORM\OneToOne(mappedBy: 'animal', cascade: ['persist', 'remove'])]
    private ?Sponsorships $sponsorships = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    private ?Users $user = null;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->created_at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRaces(): ?Races
    {
        return $this->races;
    }

    public function setRaces(?Races $races): self
    {
        $this->races = $races;

        return $this;
    }

    public function getSex(): ?Sex
    {
        return $this->sex;
    }

    public function setSex(?Sex $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRefuge(): ?Refuges
    {
        return $this->refuge;
    }

    public function setRefuge(?Refuges $refuge): self
    {
        $this->refuge = $refuge;

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setAnimals($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAnimals() === $this) {
                $image->setAnimals(null);
            }
        }

        return $this;
    }

    public function getAdoption(): ?Adoption
    {
        return $this->adoption;
    }

    public function setAdoption(Adoption $adoption): self
    {
        // set the owning side of the relation if necessary
        if ($adoption->getAnimal() !== $this) {
            $adoption->setAnimal($this);
        }

        $this->adoption = $adoption;

        return $this;
    }

    public function getSponsorships(): ?Sponsorships
    {
        return $this->sponsorships;
    }

    public function setSponsorships(Sponsorships $sponsorships): self
    {
        // set the owning side of the relation if necessary
        if ($sponsorships->getAnimal() !== $this) {
            $sponsorships->setAnimal($this);
        }

        $this->sponsorships = $sponsorships;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }
}
