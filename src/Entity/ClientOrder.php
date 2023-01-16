<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ClientOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClientOrderRepository::class)]
#[ApiResource]
class ClientOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateheureCommande = null;

    #[ORM\Column]
    private ?int $prixCommmande = null;

    #[ORM\Column(length: 50)]
    private ?string $statutCommande = null;

    #[ORM\ManyToOne(inversedBy: 'clientOrders')]
    #[Groups(["create","update"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?ClientTable $tableCommande = null;

    #[ORM\ManyToOne(inversedBy: 'clientOrders')]
    #[Groups(["create","update"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Serveur = null;

    #[ORM\ManyToMany(targetEntity: Plat::class, inversedBy: 'clientOrders')]
    private Collection $Plats;

    #[ORM\Column(length: 255)]
    private ?string $Client = null;

    public function __construct()
    {
        $this->Plats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateheureCommande(): ?\DateTimeInterface
    {
        return $this->dateheureCommande;
    }

    public function setDateheureCommande(\DateTimeInterface $dateheureCommande): self
    {
        $this->dateheureCommande = $dateheureCommande;

        return $this;
    }

    public function getPrixCommmande(): ?int
    {
        return $this->prixCommmande;
    }

    public function setPrixCommmande(int $prixCommmande): self
    {
        $this->prixCommmande = $prixCommmande;

        return $this;
    }

    public function getStatutCommande(): ?string
    {
        return $this->statutCommande;
    }

    public function setStatutCommande(string $statutCommande): self
    {
        $this->statutCommande = $statutCommande;

        return $this;
    }

    public function getTableCommande(): ?ClientTable
    {
        return $this->tableCommande;
    }

    public function setTableCommande(?ClientTable $tableCommande): self
    {
        $this->tableCommande = $tableCommande;

        return $this;
    }

    public function getServeur(): ?User
    {
        return $this->Serveur;
    }

    public function setServeur(?User $Serveur): self
    {
        $this->Serveur = $Serveur;

        return $this;
    }

    /**
     * @return Collection<int, Plat>
     */
    public function getPlats(): Collection
    {
        return $this->Plats;
    }

    public function addPlat(Plat $plat): self
    {
        if (!$this->Plats->contains($plat)) {
            $this->Plats->add($plat);
        }

        return $this;
    }

    public function removePlat(Plat $plat): self
    {
        $this->Plats->removeElement($plat);

        return $this;
    }

    public function getClient(): ?string
    {
        return $this->Client;
    }

    public function setClient(string $Client): self
    {
        $this->Client = $Client;

        return $this;
    }
}
