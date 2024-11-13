<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface PackInterface
{
    public function getId(): ?string;

    public function getShorthand(): ?string;

    public function getName(): ?string;

    /**
     * @return Collection<int, Printing>
     */
    public function getPrintings(): Collection;
}
