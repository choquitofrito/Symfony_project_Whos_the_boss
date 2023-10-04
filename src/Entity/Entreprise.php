<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $secteur = null;

    #[ORM\Column(length: 255)]
    private ?string $emplacement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Cotation::class)]
    private Collection $cotation;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Avis::class)]
    private Collection $avis;

    #[ORM\Column(length: 800)]
    private ?string $description = null;

        //constructeur et hydrate

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
            $this->cotation = new ArrayCollection();
            $this->avis = new ArrayCollection();
        }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSecteur(): ?string
    {
        return $this->secteur;
    }

    public function setSecteur(string $secteur): static
    {
        $this->secteur = $secteur;

        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(string $emplacement): static
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Cotation>
     */
    public function getCotation(): Collection
    {
        return $this->cotation;
    }

    public function addCotation(Cotation $cotation): static
    {
        if (!$this->cotation->contains($cotation)) {
            $this->cotation->add($cotation);
            $cotation->setEntreprise($this);
        }

        return $this;
    }

    public function removeCotation(Cotation $cotation): static
    {
        if ($this->cotation->removeElement($cotation)) {
            // set the owning side to null (unless already changed)
            if ($cotation->getEntreprise() === $this) {
                $cotation->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvis(Avis $avis): static
    {
        if (!$this->avis->contains($avis)) {
            $this->avis->add($avis);
            $avis->setEntreprise($this);
        }

        return $this;
    }

    public function removeAvis(Avis $avis): static
    {
        if ($this->avis->removeElement($avis)) {
            // set the owning side to null (unless already changed)
            if ($avis->getEntreprise() === $this) {
                $avis->setEntreprise(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
