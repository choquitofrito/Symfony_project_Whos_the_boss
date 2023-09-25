<?php

namespace App\Entity;

use App\Repository\CritereRepository;
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
}
