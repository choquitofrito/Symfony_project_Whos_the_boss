<?php

namespace App\Entity;

use App\Repository\CritereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CritereRepository::class)]
class Critere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 800)]
    private ?string $questioncritere = null;

    #[ORM\ManyToMany(targetEntity: Cotation::class, inversedBy: 'criteres')]
    private Collection $cotations;

    public function __construct()
    {
        $this->cotations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestioncritere(): ?string
    {
        return $this->questioncritere;
    }

    public function setQuestioncritere(string $questioncritere): static
    {
        $this->questioncritere = $questioncritere;

        return $this;
    }

    /**
     * @return Collection<int, Cotation>
     */
    public function getCotations(): Collection
    {
        return $this->cotations;
    }

    public function addCotation(Cotation $cotation): static
    {
        if (!$this->cotations->contains($cotation)) {
            $this->cotations->add($cotation);
        }

        return $this;
    }

    public function removeCotation(Cotation $cotation): static
    {
        $this->cotations->removeElement($cotation);

        return $this;
    }
}
