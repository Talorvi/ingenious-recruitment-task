<?php

namespace App\Modules\Invoices\Application\Mappers;

use App\Modules\Invoices\Api\Dto\InvoiceLineItemDto;
use App\Modules\Invoices\Api\Dto\ProductDto;
use App\Modules\Invoices\Domain\Entities\InvoiceLineItem;

class InvoiceLineItemToInvoiceLineItemDtoMapper
{
    public static function map(InvoiceLineItem $lineItem): InvoiceLineItemDto
    {
        $productDto = new ProductDto(
            id: $lineItem->getProduct()->getId()->toString(),
            name: $lineItem->getProduct()->getName(),
            price: $lineItem->getProduct()->getPrice(),
            currency: $lineItem->getProduct()->getCurrency()
        );

        $totalPrice = $lineItem->getProduct()->getPrice() * $lineItem->getQuantity();

        return new InvoiceLineItemDto(
            product: $productDto,
            quantity: $lineItem->getQuantity(),
            totalPrice: $totalPrice);
    }
}
