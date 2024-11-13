<?php

declare(strict_types=1);

namespace App\Entity;

abstract class AbstractPrinting implements PrintingInterface, \Stringable
{
    #[\Override]
    public function __toString(): string
    {
        return sprintf('%s (#%s) x %s (#%s)', $this->getCard()->getName(), $this->getCard()->getId(), $this->getPack()->getName(), $this->getPack()->getId());
    }
}
