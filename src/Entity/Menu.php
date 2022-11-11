<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(length: 255)]
    private ?string $Calories = null;

    #[ORM\Column(length: 255)]
    private ?string $Price = null;

    #[ORM\Column(length: 255)]
    private ?string $Image = null;

    #[ORM\ManyToOne(inversedBy: 'menus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $UserId = null;

    #[ORM\OneToMany(mappedBy: 'MenuId', targetEntity: MenuPlat::class)]
    private Collection $menuPlats;

    public function __construct()
    {
        $this->menuPlats = new ArrayCollection();
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

    public function getCalories(): ?string
    {
        return $this->Calories;
    }

    public function setCalories(string $Calories): self
    {
        $this->Calories = $Calories;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->Price;
    }

    public function setPrice(string $Price): self
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

    public function getUserId(): ?User
    {
        return $this->UserId;
    }

    public function setUserId(?User $UserId): self
    {
        $this->UserId = $UserId;

        return $this;
    }

    /**
     * @return Collection<int, MenuPlat>
     */
    public function getMenuPlats(): Collection
    {
        return $this->menuPlats;
    }

    public function addMenuPlat(MenuPlat $menuPlat): self
    {
        if (!$this->menuPlats->contains($menuPlat)) {
            $this->menuPlats->add($menuPlat);
            $menuPlat->setMenuId($this);
        }

        return $this;
    }

    public function removeMenuPlat(MenuPlat $menuPlat): self
    {
        if ($this->menuPlats->removeElement($menuPlat)) {
            // set the owning side to null (unless already changed)
            if ($menuPlat->getMenuId() === $this) {
                $menuPlat->setMenuId(null);
            }
        }

        return $this;
    }
}
