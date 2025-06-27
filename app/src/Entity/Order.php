<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?bool $validation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $paye = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $delivery_time = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $delivry_max_date = null;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'orders')]
    private Collection $product;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Adress $adress = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $users = null;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function isValidation(): ?bool
    {
        return $this->validation;
    }

    public function setValidation(?bool $validation): static
    {
        $this->validation = $validation;

        return $this;
    }

    public function isPaye(): ?bool
    {
        return $this->paye;
    }

    public function setPaye(?bool $paye): static
    {
        $this->paye = $paye;

        return $this;
    }

    public function getDeliveryTime(): ?\DateTimeImmutable
    {
        return $this->delivery_time;
    }

    public function setDeliveryTime(?\DateTimeImmutable $delivery_time): static
    {
        $this->delivery_time = $delivery_time;

        return $this;
    }

    public function getDelivryMaxDate(): ?\DateTimeImmutable
    {
        return $this->delivry_max_date;
    }

    public function setDelivryMaxDate(?\DateTimeImmutable $delivry_max_date): static
    {
        $this->delivry_max_date = $delivry_max_date;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->product->contains($product)) {
            $this->product->add($product);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        $this->product->removeElement($product);

        return $this;
    }

    public function getAdress(): ?Adress
    {
        return $this->adress;
    }

    public function setAdress(?Adress $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): static
    {
        $this->users = $users;

        return $this;
    }
}
