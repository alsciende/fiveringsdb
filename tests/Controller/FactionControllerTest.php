<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\FactionController;
use App\Enum\Clan;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[CoversClass(FactionController::class)]
class FactionControllerTest extends WebTestCase
{
    /**
     * @return array<array<string>>
     */
    public static function factionProvider(): array
    {
        return array_map(fn (Clan $faction): array => [$faction->value], Clan::cases());
    }

    #[DataProvider('factionProvider')]
    public function testFactionPage(string $factionId): void
    {
        $client = static::createClient();
        $crawler = $client->request(\Symfony\Component\HttpFoundation\Request::METHOD_GET, '/faction/' . $factionId);

        $this->assertResponseIsSuccessful();
        $this->assertGreaterThan(0, $crawler->filter('table.card-list tbody tr')->count(), 'Empty card list!');
    }
}
