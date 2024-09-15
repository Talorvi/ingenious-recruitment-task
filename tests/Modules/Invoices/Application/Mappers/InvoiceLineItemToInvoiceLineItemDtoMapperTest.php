<?php

namespace Tests\Modules\Invoices\Application\Mappers;

use Tests\TestCase;
use App\Modules\Invoices\Application\Mappers\InvoiceLineItemToInvoiceLineItemDtoMapper;
use App\Modules\Invoices\Domain\Entities\InvoiceLineItem;
use App\Modules\Invoices\Domain\Entities\Product;
use Ramsey\Uuid\Uuid;

class InvoiceLineItemToInvoiceLineItemDtoMapperTest extends TestCase
{
    public function test_map_returns_correct_invoice_line_item_dto()
    {
        $product = new Product(Uuid::uuid4(), 'Test Product', 50.0, 'USD');
        $quantity = 2;
        $lineItem = new InvoiceLineItem(Uuid::uuid4(), $product, $quantity);

        $lineItemDto = InvoiceLineItemToInvoiceLineItemDtoMapper::map($lineItem);

        $this->assertEquals($product->getId()->toString(), $lineItemDto->product->id);
        $this->assertEquals($product->getName(), $lineItemDto->product->name);
        $this->assertEquals($product->getPrice(), $lineItemDto->product->price);
        $this->assertEquals($product->getCurrency(), $lineItemDto->product->currency);
        $this->assertEquals($quantity, $lineItemDto->quantity);
        $this->assertEquals($product->getPrice() * $quantity, $lineItemDto->totalPrice);
    }
}
