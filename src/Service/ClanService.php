<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\Clan;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Provides data about Clans.
 */
readonly class ClanService
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {
    }

    /**
     * Return an array of id => name for all clans.
     *
     * @return array<string,string>
     */
    public function all(): array
    {
        $clans = [];

        foreach (Clan::cases() as $case) {
            $clans[$case->value] = $this->translator->trans($case->value, domain: 'clans');
        }

        return $clans;
    }
}
