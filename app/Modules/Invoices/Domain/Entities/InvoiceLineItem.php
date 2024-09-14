<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Entities;

use Ramsey\Uuid\UuidInterface;

class InvoiceLineItem
{
    private UuidInterface $id;
    private Product $product;
    private int $quantity;

    public function __construct(UuidInterface $id, Product $product, int $quantity)
    {
        $this->id = $id;
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
