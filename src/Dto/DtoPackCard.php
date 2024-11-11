<?php

declare(strict_types=1);

namespace App\Dto;

class DtoPackCard
{
    public string $cardId;
    public string $packId;
    public string $position;
    public int $quantity;
    public string $illustrator;
    public string $flavorText;
    public string $imageUrl;
}
