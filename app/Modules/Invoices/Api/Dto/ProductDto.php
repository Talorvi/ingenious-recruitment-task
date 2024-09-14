<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Api\Dto;

class ProductDto
{
    public string $id;
    public string $name;
    public float $price;
    public string $currency;

    public function __construct(string $id, string $name, float $price, string $currency)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->currency = $currency;
    }
}
