<?php

namespace Tests\Modules\Invoices\Domain\Entities;

use App\Modules\Invoices\Domain\Entities\InvoiceLineItem;
use App\Modules\Invoices\Domain\Entities\Product;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class InvoiceLineItemTest extends TestCase
{
    public function test_getters_return_correct_values()
    {
        $id = Uuid::uuid4();
        $product = $this->createMockProduct();
        $quantity = 5;

        $lineItem = new InvoiceLineItem($id, $product, $quantity);

        $this->assertSame($id, $lineItem->getId());
        $this->assertSame($product, $lineItem->getProduct());
        $this->assertEquals($quantity, $lineItem->getQuantity());
    }

    private function createMockProduct(): Product
    {
        return new Product(
            Uuid::uuid4(),
            'Test Product',
            99.99,
            'USD'
        );
    }
}
