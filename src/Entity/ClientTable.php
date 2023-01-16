<?php

namespace App\Entity;

use App\Repository\ClientTableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientTableRepository::class)]
class ClientTable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numTable = null;

    #[ORM\Column(length: 255)]
    private ?string $nomSalle = null;

    #[ORM\OneToMany(mappedBy: 'tableCommande', targetEntity: ClientOrder::class)]
    private Collection $clientOrders;

    public function __construct()
    {
        $this->clientOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumTable(): ?int
    {
        return $this->numTable;
    }

    public function setNumTable(int $numTable): self
    {
        $this->numTable = $numTable;

        return $this;
    }

    public function getNomSalle(): ?string
    {
        return $this->nomSalle;
    }

    public function setNomSalle(string $nomSalle): self
    {
        $this->nomSalle = $nomSalle;

        return $this;
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
            $clientOrder->setTableCommande($this);
        }

        return $this;
    }

    public function removeClientOrder(ClientOrder $clientOrder): self
    {
        if ($this->clientOrders->removeElement($clientOrder)) {
            // set the owning side to null (unless already changed)
            if ($clientOrder->getTableCommande() === $this) {
                $clientOrder->setTableCommande(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nomSalle;
    }
}
