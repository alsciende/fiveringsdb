<?php

declare(strict_types=1);

namespace App\Entity;

interface PackCardInterface
{
    public function getCard(): ?Card;

    public function getPack(): ?Pack;

    public function getPosition(): ?string;

    public function getIllustrator(): ?string;

    public function getFlavorText(): ?string;

    public function getImageUrl(): ?string;
}
