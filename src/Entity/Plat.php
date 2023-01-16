<?php

namespace App\Entity;


use ApiPlatform\Metadata\ApiResource;
use App\Repository\PlatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlatRepository::class)]
#[ApiResource]
class Plat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column]
    private ?int $Calories = null;

    #[ORM\Column]
    private ?float $Price = null;

    #[ORM\Column(length: 255)]
    private ?string $Image = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Description = null;

    #[ORM\Column]
    private ?bool $Sticky = null;

    #[ORM\ManyToOne(inversedBy: 'plats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $Category = null;

    #[ORM\ManyToOne(inversedBy: 'plats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[ORM\ManyToMany(targetEntity: Allergen::class, inversedBy: 'plats')]
    private Collection $allergens;

    #[ORM\ManyToMany(targetEntity: ClientOrder::class, mappedBy: 'Plats')]
    private Collection $clientOrders;

    public function __construct()
    {
        $this->allergens = new ArrayCollection();
        $this->clientOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getCalories(): ?int
    {
        return $this->Calories;
    }

    public function setCalories(int $Calories): self
    {
        $this->Calories = $Calories;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(float $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function isSticky(): ?bool
    {
        return $this->Sticky;
    }

    public function setSticky(bool $Sticky): self
    {
        $this->Sticky = $Sticky;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function addUser(User $User): self
    {
        if (!$this->User->contains($User)) {
            $this->allergens->add($allergen);
        }

        return $this;
    }

    /**
     * @return Collection<int, Allergen>
     */
    public function getAllergens(): Collection
    {
        return $this->allergens;
    }

    public function addAllergen(Allergen $allergen): self
    {
        if (!$this->allergens->contains($allergen)) {
            $this->allergens->add($allergen);
        }

        return $this;
    }

    public function removeAllergen(Allergen $allergen): self
    {
        $this->allergens->removeElement($allergen);

        return $this;
    }
    public function __toString(): string
    {
        return $this->Name;
    }

    /**
     * @return Collection<int, ClientOrder>
     */
    public function getClientOrders(): Collection
    {
        return $this->clientOrders;
    }

    public function addClientOrder(ClientOrder $clientOrder): self
    {
        if (!$this->clientOrders->contains($clientOrder)) {
            $this->clientOrders->add($clientOrder);
            $clientOrder->addPlat($this);
        }

        return $this;
    }

    public function removeClientOrder(ClientOrder $clientOrder): self
    {
        if ($this->clientOrders->removeElement($clientOrder)) {
            $clientOrder->removePlat($this);
        }

        return $this;
    }
}
