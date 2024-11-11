<?php

declare(strict_types=1);

namespace App\Tests\Enum;

use App\Enum\Clan;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Clan::class)]
class FactionTest extends TestCase
{
    public function testSize(): void
    {
        $cases = Clan::cases();
        $this->assertCount(10, $cases);
    }
}
