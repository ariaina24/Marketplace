<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
/**
 * @ORM\Id()
 * @ORM\GeneratedValue()
 * @ORM\Column(type="integer")
 */
private $id;

/**
 * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commandes")
 * @ORM\JoinColumn(nullable=false)
 */
private $user;

/**
 * @ORM\Column(type="datetime")
 */
private $dateCommande;

/**
 * @ORM\Column(type="string", length=255)
 */
private $livraison;

/**
 * @ORM\Column(type="boolean")
 */
private $paye;

/**
 * @ORM\Column(type="string", length=255)
 */
private $transporteurName;

/**
 * @ORM\Column(type="float")
 */
private $transporteurPrix;

/**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripeSessionId;
/**
 * @ORM\OneToMany(targetEntity=DetailCommande::class, mappedBy="commande", cascade={"persist", "remove"})
 */
private $detailCommandes;

public function __construct()
{
    $this->detailCommandes = new ArrayCollection();
}

public function getId(): ?int
{
    return $this->id;
}

public function getUser(): ?User
{
    return $this->user;
}

public function setUser(?User $user): self
{
    $this->user = $user;

    return $this;
}

public function getDateCommande(): ?\DateTimeInterface
{
    return $this->dateCommande;
}

public function setDateCommande(\DateTimeInterface $dateCommande): self
{
    $this->dateCommande = $dateCommande;

    return $this;
}

public function getLivraison(): ?string
{
    return $this->livraison;
}

public function setLivraison(string $livraison): self
{
    $this->livraison = $livraison;

    return $this;
}

public function isPaye(): ?bool
{
    return $this->paye;
}

public function setPaye(bool $paye): self
{
    $this->paye = $paye;

    return $this;
}

public function getTransporteurName(): ?string
{
    return $this->transporteurName;
}

public function setTransporteurName(string $transporteurName): self
{
    $this->transporteurName = $transporteurName;

    return $this;
}

public function getTransporteurPrix(): ?float
{
    return $this->transporteurPrix;
}

public function setTransporteurPrix(float $transporteurPrix): self
{
    $this->transporteurPrix = $transporteurPrix;

    return $this;
}

/**
 * @return Collection|DetailCommande[]
 */
public function getDetailCommandes(): Collection
{
    return $this->detailCommandes;
}
public function addDetailCommande(DetailCommande $detailCommande): self
{
    if (!$this->detailCommandes->contains($detailCommande)) {
        $this->detailCommandes[] = $detailCommande;
        $detailCommande->setCommande($this);
    }

    return $this;
}

public function removeDetailCommande(DetailCommande $detailCommande): self
{
    if ($this->detailCommandes->removeElement($detailCommande)) {
        // set the owning side to null (unless already changed)
        if ($detailCommande->getCommande() === $this) {
            $detailCommande->setCommande(null);
        }
    }

    return $this;
}

	/**
	 * 
	 * @return mixed
	 */
	public function getStripeSessionId() {
		return $this->stripeSessionId;
	}
	
	/**
	 * 
	 * @param mixed $stripeSessionId 
	 * @return self
	 */
	public function setStripeSessionId($stripeSessionId): self {
		$this->stripeSessionId = $stripeSessionId;
		return $this;
	}

	/**
	 * 
	 * @return mixed
	 */
	public function getReference() {
		return $this->reference;
	}
	
	/**
	 * 
	 * @param mixed $reference 
	 * @return self
	 */
	public function setReference($reference): self {
		$this->reference = $reference;
		return $this;
	}
}