<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Entities;

use Ramsey\Uuid\UuidInterface;

class Product
{
    private UuidInterface $id;
    private string $name;
    private float $price;
    private string $currency;

    public function __construct(UuidInterface $id, string $name, float $price, string $currency)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->currency = $currency;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
