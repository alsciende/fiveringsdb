<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PrintingRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Cache;

#[ORM\Entity(repositoryClass: PrintingRepository::class)]
#[Cache(usage: 'READ_ONLY')]
class Printing extends AbstractPrinting
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Card::class, inversedBy: 'printings')]
    #[ORM\JoinColumn(name: 'card_id', referencedColumnName: 'id')]
    private ?Card $card = null;

    #[ORM\ManyToOne(targetEntity: Pack::class, inversedBy: 'printings')]
    #[ORM\JoinColumn(name: 'pack_id', referencedColumnName: 'id')]
    private ?Pack $pack = null;

    #[ORM\Column]
    private int $quantity;

    #[ORM\Column]
    private int $position;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $illustrator = null;

    #[ORM\Column(length: 1023, nullable: true)]
    private ?string $flavor_text = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image_url = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    #[\Override]
    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    #[\Override]
    public function getIllustrator(): ?string
    {
        return $this->illustrator;
    }

    public function setIllustrator(string $illustrator): static
    {
        $this->illustrator = $illustrator;

        return $this;
    }

    #[\Override]
    public function getFlavorText(): ?string
    {
        return $this->flavor_text;
    }

    public function setFlavorText(string $flavor_text): static
    {
        $this->flavor_text = $flavor_text;

        return $this;
    }

    #[\Override]
    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): static
    {
        $this->image_url = $image_url;

        return $this;
    }

    #[\Override]
    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): static
    {
        $this->card = $card;

        return $this;
    }

    #[\Override]
    public function getPack(): ?Pack
    {
        return $this->pack;
    }

    public function setPack(?Pack $pack): static
    {
        $this->pack = $pack;

        return $this;
    }
}
