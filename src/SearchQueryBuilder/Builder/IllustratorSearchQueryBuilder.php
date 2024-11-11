<?php

declare(strict_types=1);

namespace App\SearchQueryBuilder\Builder;

use App\Search\Operand;
use App\Search\Operator;
use Doctrine\ORM\QueryBuilder;

class IllustratorSearchQueryBuilder extends AbstractSearchQueryBuilder
{
    #[\Override]
    public function getName(): Operand
    {
        return Operand::Illustrator;
    }

    #[\Override]
    protected function buildQuery(QueryBuilder $queryBuilder, Operand $operand, Operator $operator, string $value, string $identifier): void
    {
        $queryBuilder
            ->andWhere("pc.illustrator {$this->getOperator($operator)} :{$identifier}")
            ->setParameter($identifier, $value);
    }
}
