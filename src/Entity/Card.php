<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\{Clan, Element, Role, Side, Type};
use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Cache;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: CardRepository::class)]
#[Cache(usage: 'READ_ONLY')]
class Card extends AbstractCard
{
    #[ORM\Id]
    #[ORM\Column(length: 50)]
    private ?string $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, enumType: Clan::class)]
    private ?Clan $clan = null;

    #[ORM\Column(nullable: true)]
    private ?int $cost = null;

    #[ORM\Column(type: Types::STRING, enumType: Type::class)]
    private ?Type $type = null;

    /**
     * @var array<string>|null
     */
    #[ORM\Column(type: 'text[]', nullable: true)]
    private ?array $traits = null;

    #[ORM\Column('text', length: 1024, nullable: true)]
    private ?string $text = null;

    /**
     * @var array<string>|null
     */
    #[ORM\Column(type: 'text[]', nullable: true)]
    private ?array $allowedClans = null;

    #[ORM\Column(nullable: true)]
    private ?int $deckLimit = null;

    /**
     * @var array<string>|null
     */
    #[ORM\Column(type: 'text[]', nullable: true)]
    private ?array $elements = null;

    #[ORM\Column(nullable: true)]
    private ?int $fate = null;

    #[ORM\Column(nullable: true)]
    private ?int $glory = null;

    #[ORM\Column(nullable: true)]
    private ?int $honor = null;

    #[ORM\Column(nullable: true)]
    private ?int $influenceCost = null;

    #[ORM\Column(nullable: true)]
    private ?int $influencePool = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $military = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $militaryBonus = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $political = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $politicalBonus = null;

    #[ORM\Column(type: Types::STRING, nullable: true, enumType: Role::class)]
    private ?Role $roleRestriction = null;

    #[ORM\Column(type: Types::STRING, enumType: Side::class)]
    private ?Side $side = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $strength = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $strengthBonus = null;

    #[ORM\Column]
    private ?bool $uniqueness = null;

    /**
     * @var Collection<int, PackCard>
     */
    #[ORM\OneToMany(targetEntity: PackCard::class, mappedBy: 'card', cascade: ['persist', 'remove'])]
    #[Ignore]
    private Collection $packCards;

    public function __construct()
    {
        $this->packCards = new ArrayCollection();
    }

    #[\Override]
    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    #[\Override]
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getClan(): ?Clan
    {
        return $this->clan;
    }

    public function setClan(Clan $clan): static
    {
        $this->clan = $clan;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(?int $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(Type $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array<string>|null
     */
    public function getTraits(): ?array
    {
        return $this->traits;
    }

    public function formatTraits(): string
    {
        return implode(' ', array_map(
            fn (string $trait) => ucfirst($trait) . '.',
            $this->traits
        ));
    }

    /**
     * @param array<string> $traits
     *
     * @return $this
     */
    public function setTraits(array $traits): static
    {
        $this->traits = $traits;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection<int, PackCard>
     */
    #[\Override]
    public function getPackCards(): Collection
    {
        return $this->packCards;
    }

    #[\Override]
    public function addPackCard(PackCard $packCard): static
    {
        if (! $this->packCards->contains($packCard)) {
            $this->packCards->add($packCard);
            $packCard->setCard($this);
        }

        return $this;
    }

    #[\Override]
    public function removePackCard(PackCard $packCard): static
    {
        if ($this->packCards->removeElement($packCard)) {
            // set the owning side to null (unless already changed)
            if ($packCard->getCard() === $this) {
                $packCard->setCard(null);
            }
        }

        return $this;
    }

    /**
     * @return array<Pack>
     */
    #[Ignore]
    public function getPacks(): array
    {
        $packs = [];
        foreach ($this->getPackCards() as $packCard) {
            $packs[] = $packCard->getPack();
        }

        return $packs;
    }

    /**
     * @return string[]|null
     */
    public function getAllowedClans(): ?array
    {
        return $this->allowedClans;
    }

    /**
     * @param string[]|null $allowedClans
     */
    public function setAllowedClans(?array $allowedClans): void
    {
        $this->allowedClans = $allowedClans;
    }

    public function getDeckLimit(): ?int
    {
        return $this->deckLimit;
    }

    public function setDeckLimit(?int $deckLimit): void
    {
        $this->deckLimit = $deckLimit;
    }

    /**
     * @return Element[]|null
     */
    public function getElements(): ?array
    {
        return array_map(
            fn (string $element): Element => Element::from($element),
            $this->elements
        );
    }

    /**
     * @param string[]|Element[]|null $elements
     */
    public function setElements(?array $elements): void
    {
        $this->elements = is_array($elements) ? array_map(
            fn (string|Element $element) => $element instanceof Element ? $element->value : $element,
            $elements
        ) : null;
    }

    public function getFate(): ?int
    {
        return $this->fate;
    }

    public function setFate(?int $fate): void
    {
        $this->fate = $fate;
    }

    public function getGlory(): ?int
    {
        return $this->glory;
    }

    public function setGlory(?int $glory): void
    {
        $this->glory = $glory;
    }

    public function getHonor(): ?int
    {
        return $this->honor;
    }

    public function setHonor(?int $honor): void
    {
        $this->honor = $honor;
    }

    public function getInfluenceCost(): ?int
    {
        return $this->influenceCost;
    }

    public function setInfluenceCost(?int $influenceCost): void
    {
        $this->influenceCost = $influenceCost;
    }

    public function getInfluencePool(): ?int
    {
        return $this->influencePool;
    }

    public function setInfluencePool(?int $influencePool): void
    {
        $this->influencePool = $influencePool;
    }

    public function getMilitary(): ?string
    {
        return $this->military;
    }

    public function setMilitary(?string $military): void
    {
        $this->military = $military;
    }

    public function getMilitaryBonus(): ?string
    {
        return $this->militaryBonus;
    }

    public function setMilitaryBonus(?string $militaryBonus): void
    {
        $this->militaryBonus = $militaryBonus;
    }

    public function getPolitical(): ?string
    {
        return $this->political;
    }

    public function setPolitical(?string $political): void
    {
        $this->political = $political;
    }

    public function getPoliticalBonus(): ?string
    {
        return $this->politicalBonus;
    }

    public function setPoliticalBonus(?string $politicalBonus): void
    {
        $this->politicalBonus = $politicalBonus;
    }

    public function getRoleRestriction(): ?Role
    {
        return $this->roleRestriction;
    }

    public function setRoleRestriction(?Role $roleRestriction): void
    {
        $this->roleRestriction = $roleRestriction;
    }

    public function getSide(): ?Side
    {
        return $this->side;
    }

    public function setSide(?Side $side): void
    {
        $this->side = $side;
    }

    public function getStrength(): ?string
    {
        return $this->strength;
    }

    public function setStrength(?string $strength): void
    {
        $this->strength = $strength;
    }

    public function getStrengthBonus(): ?string
    {
        return $this->strengthBonus;
    }

    public function setStrengthBonus(?string $strengthBonus): void
    {
        $this->strengthBonus = $strengthBonus;
    }

    public function getUniqueness(): ?bool
    {
        return $this->uniqueness;
    }

    public function setUniqueness(?bool $uniqueness): void
    {
        $this->uniqueness = $uniqueness;
    }

    /**
     * @TODO move me elsewhere
     */
    public function typeQuery(): string
    {
        return sprintf('t:%s%s',
            $this->getType()->value,
            empty($this->getTraits())
                ? ''
                : ' ' . implode(
                    ' ',
                    array_map(
                        fn (string $trait): string => sprintf('s:%s', $trait),
                        $this->getTraits()
                    )
                )
        );
    }
}
