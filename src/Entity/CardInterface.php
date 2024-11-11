<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface CardInterface
{
    public function getId(): ?string;

    public function getName(): ?string;

    /**
     * @return Collection<int, PackCard>
     */
    public function getPackCards(): Collection;

    public function addPackCard(PackCard $packCard): static;

    public function removePackCard(PackCard $packCard): static;
}
