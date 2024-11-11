<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PackCardRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Cache;

#[ORM\Entity(repositoryClass: PackCardRepository::class)]
#[Cache(usage: 'READ_ONLY')]
class PackCard extends AbstractPackCard
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Card::class, inversedBy: 'packCards')]
    #[ORM\JoinColumn(name: 'card_id', referencedColumnName: 'id')]
    private ?Card $card = null;

    #[ORM\ManyToOne(targetEntity: Pack::class, inversedBy: 'packCards')]
    #[ORM\JoinColumn(name: 'pack_id', referencedColumnName: 'id')]
    private ?Pack $pack = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?int $position = null;

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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
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

    public function setFlavorText(?string $flavor_text): static
    {
        $this->flavor_text = $flavor_text;

        return $this;
    }

    #[\Override]
    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(?string $image_url): static
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

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'card_id' => $this->card->getId(),
            'pack_id' => $this->pack->getId(),
            'quantity' => $this->quantity,
            'position' => $this->position,
            'illustrator' => $this->illustrator,
            'flavor_text' => $this->flavor_text,
            'image_url' => $this->image_url,
        ];
    }

    public function updateFrom(self $packCard): void
    {
        $this->setQuantity($packCard->getQuantity())
            ->setPosition($packCard->getPosition())
            ->setIllustrator($packCard->getIllustrator())
            ->setFlavorText($packCard->getFlavorText())
            ->setImageUrl($packCard->getImageUrl())
        ;
    }
}
