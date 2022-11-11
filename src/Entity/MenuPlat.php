<?php

namespace App\Entity;

use App\Repository\MenuPlatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuPlatRepository::class)]
class MenuPlat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'menuPlats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Menu $MenuId = null;

    #[ORM\ManyToOne(inversedBy: 'menuPlats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plat $PlatId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMenuId(): ?Menu
    {
        return $this->MenuId;
    }

    public function setMenuId(?Menu $MenuId): self
    {
        $this->MenuId = $MenuId;

        return $this;
    }

    public function getPlatId(): ?Plat
    {
        return $this->PlatId;
    }

    public function setPlatId(?Plat $PlatId): self
    {
        $this->PlatId = $PlatId;

        return $this;
    }
}
