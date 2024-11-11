<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Pack;
use App\Enum\Clan;
use App\Enum\Type;
use App\Search\AdvancedCardSearch;
use App\Search\Operand;

/**
 * Encodes search data into query strings.
 */
class SyntaxEncoder
{
    private function formatValue(string $value): string
    {
        return preg_match('/[^\w-]/', $value) ? '"' . $value . '"' : $value;
    }

    public function encode(AdvancedCardSearch $search): string
    {
        $queryParts = [];

        if (is_string($search->getName())) {
            $queryParts[] = sprintf('%s', $this->formatValue($search->getName()));
        }

        if ($search->getClan() instanceof Clan) {
            $value = $search->getClan()->value;
            $queryParts[] = sprintf('%s:%s', Operand::Clan->value, $value);
        }

        if (is_int($search->getCost())) {
            $queryParts[] = sprintf('%s:%d', Operand::Cost->value, $search->getCost());
        }

        if ($search->getType() instanceof Type) {
            $queryParts[] = sprintf('%s:%s', Operand::Type->value, $search->getType()->value);
        }

        if (is_string($search->getTrait())) {
            $queryParts[] = sprintf('%s:%s', Operand::Traits->value, $this->formatValue($search->getTrait()));
        }

        if (is_string($search->getText())) {
            $queryParts[] = sprintf('%s:%s', Operand::Text->value, $this->formatValue($search->getText()));
        }

        if ($search->getPack() instanceof Pack) {
            $queryParts[] = sprintf('%s:%s', Operand::Pack->value, $search->getPack()->getId());
        }

        if (is_string($search->getIllustrator())) {
            $queryParts[] = sprintf('%s:%s', Operand::Illustrator->value, $this->formatValue($search->getIllustrator()));
        }

        return implode(' ', $queryParts);
    }
}
