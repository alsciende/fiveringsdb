<?php

declare(strict_types=1);

namespace App\SearchQueryBuilder\Builder;

use App\Exception\BadValueException;
use App\Search\Operand;
use App\Search\Operator;
use Doctrine\ORM\QueryBuilder;

class CostSearchQueryBuilder extends AbstractSearchQueryBuilder
{
    #[\Override]
    public function getName(): Operand
    {
        return Operand::Cost;
    }

    #[\Override]
    protected function buildQuery(QueryBuilder $queryBuilder, Operand $operand, Operator $operator, string $value, string $identifier): void
    {
        if (! is_numeric($value)) {
            throw new BadValueException($value, $this->getName());
        }

        $queryBuilder
            ->andWhere("c.cost {$this->getOperator($operator, numeric: true)} :{$identifier}")
            ->setParameter($identifier, intval($value));
    }
}
