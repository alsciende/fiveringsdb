<?php

declare(strict_types=1);

namespace App\Entity;

abstract class AbstractCard implements CardInterface, \Stringable
{
    #[\Override]
    public function __toString(): string
    {
        return sprintf('%s (#%s)', $this->getName(), $this->getId());
    }
}
