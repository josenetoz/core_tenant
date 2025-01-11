<?php

declare(strict_types=1);

namespace App\Data\Cashier;

readonly class Price
{
    private function __construct(
        private string $period,
        private string $periodo,
        private string $id,
        private int $price
    ) {
    }

    public static function fromArray(array $data, string $key): self
    {
        return new self(
            period: $data['period'] ?? $key,
            periodo: $data['periodo'] ?? $key,
            id: $data['id'],
            price: $data['price']
        );
    }

    public function period(): string
    {
        return $this->period;
    }


    public function periodo(): string
    {
        return $this->periodo;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function price(): int
    {
        return $this->price;
    }
}
