<?php

namespace App\Modules\Invoices\Api\Dto;

class InvoiceLineItemDto
{
    public ProductDto $product;
    public int $quantity;
    public float $totalPrice;

    public function __construct(ProductDto $product, int $quantity, float $totalPrice)
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->totalPrice = $totalPrice;
    }
}
