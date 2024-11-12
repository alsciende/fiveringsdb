<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Pack;
use App\Repository\PackRepository;

/**
 * Provides data about Packs.
 */
class PackService
{
    public function __construct(
        private readonly PackRepository $repository,
    ) {
    }

    /**
     * @return list<Pack>
     */
    public function findAll(): array
    {
        return $this->repository->findBy([], [
            'username' => 'ASC',
        ]);
    }

    /**
     * Return an array of id => name for all sets.
     *
     * @return array<string,string>
     */
    public function all(): array
    {
        $packs = [];

        foreach ($this->repository->findAll() as $pack) {
            $packs[$pack->getId()] = $pack->getName();
        }

        return $packs;
    }
}
