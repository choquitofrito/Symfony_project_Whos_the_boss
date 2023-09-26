<?php

namespace App\Entity;

use App\Repository\CotationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CotationRepository::class)]
class Cotation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\ManyToOne(inversedBy: 'cotation')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'cotation')]
    private ?Entreprise $entreprise = null;

    #[ORM\ManyToMany(targetEntity: Critere::class, mappedBy: 'cotation')]
    private Collection $criteres;

    public function hydrate (array $vals){
        foreach ($vals as $cle => $valeur){
            if (isset ($vals[$cle])){
                $nomSet = "set" . ucfirst($cle);
                $this->$nomSet ($valeur);
            }
        }
    }
    
    public function __construct(array $init)
    {
        $this->hydrate($init);
        $this->criteres = new ArrayCollection();
        // $this->entreprise = new ArrayCollection();
        // $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
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

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): static
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * @return Collection<int, Critere>
     */
    public function getCriteres(): Collection
    {
        return $this->criteres;
    }

    public function addCritere(Critere $critere): static
    {
        if (!$this->criteres->contains($critere)) {
            $this->criteres->add($critere);
            $critere->addCotation($this);
        }

        return $this;
    }

    public function removeCritere(Critere $critere): static
    {
        if ($this->criteres->removeElement($critere)) {
            $critere->removeCotation($this);
        }

        return $this;
    }
}
