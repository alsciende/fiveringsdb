<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface CardInterface
{
    public function getId(): ?string;

    public function getName(): ?string;

    /**
     * @return Collection<int, Printing>
     */
    public function getPrintings(): Collection;

    public function addPrinting(Printing $printing): static;

    public function removePrinting(Printing $printing): static;
}
