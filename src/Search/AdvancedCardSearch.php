<?php

declare(strict_types=1);

namespace App\Search;

use App\Entity\Pack;
use App\Enum\Clan;
use App\Enum\Type;

class AdvancedCardSearch
{
    private ?string $name = null;
    private ?Clan $clan = null;
    private ?int $cost = null;
    private ?Type $type = null;
    private ?string $trait = null;
    private ?string $text = null;
    private ?Pack $pack = null;
    private ?string $illustrator = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getClan(): ?Clan
    {
        return $this->clan;
    }

    public function setClan(Clan $clan): self
    {
        $this->clan = $clan;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTrait(): ?string
    {
        return $this->trait;
    }

    public function setTrait(string $trait): self
    {
        $this->trait = $trait;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getPack(): ?Pack
    {
        return $this->pack;
    }

    public function setPack(?Pack $pack): self
    {
        $this->pack = $pack;

        return $this;
    }

    public function getIllustrator(): ?string
    {
        return $this->illustrator;
    }

    public function setIllustrator(?string $illustrator): self
    {
        $this->illustrator = $illustrator;

        return $this;
    }
}
