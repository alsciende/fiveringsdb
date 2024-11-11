<?php

declare(strict_types=1);

namespace App\SearchQueryBuilder\Builder;

use App\Enum\Clan;
use App\Search\Operand;
use App\Search\Operator;
use Doctrine\ORM\QueryBuilder;

class ClanSearchQueryBuilder extends AbstractSearchQueryBuilder
{
    #[\Override]
    public function getName(): Operand
    {
        return Operand::Clan;
    }

    #[\Override]
    protected function buildQuery(QueryBuilder $queryBuilder, Operand $operand, Operator $operator, string $value, string $identifier): void
    {
        $clan = Clan::tryFrom($value);

        $queryBuilder
            ->andWhere("c.clan {$this->getOperator($operator)} :{$identifier}")
            ->setParameter($identifier, $clan);
    }
}
