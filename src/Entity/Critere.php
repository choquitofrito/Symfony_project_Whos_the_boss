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
    private Collection $cotation;

    public function __construct()
    {
        $this->cotation = new ArrayCollection();
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
    public function getCotation(): Collection
    {
        return $this->cotation;
    }

    public function addCotation(Cotation $cotation): static
    {
        if (!$this->cotation->contains($cotation)) {
            $this->cotation->add($cotation);
        }

        return $this;
    }

    public function removeCotation(Cotation $cotation): static
    {
        $this->cotation->removeElement($cotation);

        return $this;
    }
}
