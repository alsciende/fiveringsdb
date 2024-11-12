<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\ClanController;
use App\Enum\Clan;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(ClanController::class)]
class FactionControllerTest extends WebTestCase
{
    /**
     * @return array<array<string>>
     */
    public static function factionProvider(): array
    {
        return array_map(fn (Clan $clan): array => [$clan->value], Clan::cases());
    }

    #[DataProvider('factionProvider')]
    public function testFactionPage(string $clanId): void
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/clan/' . $clanId);

        $this->assertResponseIsSuccessful();
        $this->assertGreaterThan(0, $crawler->filter('table.card-list tbody tr')->count(), 'Empty card list!');
    }
}
