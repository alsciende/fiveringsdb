<?php

declare(strict_types=1);

namespace App\Search;

/**
 * Possible search operands.
 */
enum Operand: string
{
    case Name = '_';
    case Clan = 'f';
    case Type = 't';
    case Traits = 's';
    case Cost = 'c';
    case Text = 'x';
    case Pack = 'p';
    case Illustrator = 'i';
    case Quantity = 'q';
    case Position = 'n';
    case FlavorText = 'l';
}
