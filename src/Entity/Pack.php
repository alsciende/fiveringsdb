<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Cache;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: PackRepository::class)]
#[Cache(usage: 'READ_ONLY')]
class Pack extends AbstractPack
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: Types::STRING)]
    private string $id;

    #[ORM\Column(type: Types::STRING, length: 5)]
    private string $shorthand;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $size = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $release_date = null;

    /**
     * @var Collection<int, Printing>
     */
    #[ORM\OneToMany(targetEntity: Printing::class, mappedBy: 'pack')]
    #[Ignore]
    private Collection $printings;

    public function __construct()
    {
        $this->printings = new ArrayCollection();
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
    public function getShorthand(): ?string
    {
        return $this->shorthand;
    }

    public function setShorthand(?string $shorthand): void
    {
        $this->shorthand = $shorthand;
    }

    #[\Override]
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->release_date;
    }

    public function setReleaseDate(?\DateTimeInterface $release_date): static
    {
        $this->release_date = $release_date;

        return $this;
    }

    public function getCardAt(int $i): ?Printing
    {
        assert($i >= 0 && $i < $this->printings->count(),
            sprintf(
                '%s: index out of range.  Highest index: %s',
                $i,
                $this->printings->count() - 1
            ),
        );

        return $this->printings->get($i);
    }

    /**
     * @return Collection<int, Printing>
     */
    #[\Override]
    public function getPrintings(): Collection
    {
        return $this->printings;
    }

    public function addCard(Printing $card): static
    {
        if (! $this->printings->contains($card)) {
            $this->printings->add($card);
            $card->setPack($this);
        }

        return $this;
    }

    public function removeCard(Printing $card): static
    {
        if ($this->printings->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getPack() === $this) {
                $card->setPack(null);
            }
        }

        return $this;
    }
}
