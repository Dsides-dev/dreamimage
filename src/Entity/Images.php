<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImagesRepository::class)]
class Images
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(['message' => 'Veuilez indiquer un titre a votre image'])]
    #[Assert\Length([
        'max' => 70,
        'maxMessage' => 'Votre titre est trop long. La longeur max est de {{ limit }} caractères soit environ 10 mots'
    ])]
    #[ORM\Column(length: 70)]
    private ?string $title = null;


    #[Assert\NotBlank(['message' => 'Veuillez indiquer un court nom.'])]
    #[Assert\Length([
        'max' => 20,
        'maxMessage' => 'Votre titre est trop long. La longeur max est de {{ limit }} caractères soit environ 3 mots'
    ])]
    #[ORM\Column(length: 20)]
    private ?string $slug = null;

    #[Assert\NotBlank(['message' => 'Il me faut un text ce serais sympas'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $voteCountTotal = 0;

    #[ORM\Column]
    private ?bool $isVote = false;

    #[ORM\Column]
    private ?bool $isDecline = false;

    #[ORM\Column(length: 255)]
    private ?string $imagefilename = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

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

    public function getVoteCountTotal(): ?int
    {
        return $this->voteCountTotal;
    }

    public function setVoteCountTotal(int $voteCountTotal): static
    {
        $this->voteCountTotal = $voteCountTotal;

        return $this;
    }

    public function isIsVote(): ?bool
    {
        return $this->isVote;
    }

    public function setIsVote(bool $isVote): static
    {
        $this->isVote = $isVote;

        return $this;
    }

    public function isIsDecline(): ?bool
    {
        return $this->isDecline;
    }

    public function setIsDecline(bool $isDecline): static
    {
        $this->isDecline = $isDecline;

        return $this;
    }

    public function getImagefilename(): ?string
    {
        return $this->imagefilename;
    }

    public function setImagefilename(string $imagefilename): static
    {
        $this->imagefilename = $imagefilename;

        return $this;
    }
}
