<?php

declare(strict_types=1);

namespace App\SearchQueryBuilder\Builder;

use App\Exception\BadOperatorException;
use App\Search\Operand;
use App\Search\Operator;
use Doctrine\ORM\QueryBuilder;

class TraitsSearchQueryBuilder extends AbstractSearchQueryBuilder
{
    #[\Override]
    public function getName(): Operand
    {
        return Operand::Traits;
    }

    #[\Override]
    protected function buildQuery(QueryBuilder $queryBuilder, Operand $operand, Operator $operator, string $value, string $identifier): void
    {
        $test = match ($operator) {
            Operator::EQ => 'true',
            Operator::NE => 'false',
            default => throw new BadOperatorException($operator->value, $this->getName()),
        };

        $queryBuilder
            ->andWhere("IN_ARRAY(:{$identifier}, c.traits) = {$test}")
            ->setParameter($identifier, $value);
    }
}
