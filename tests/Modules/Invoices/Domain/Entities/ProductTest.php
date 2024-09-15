<?php

namespace Tests\Modules\Invoices\Domain\Entities;

use App\Modules\Invoices\Domain\Entities\Product;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_getters_return_correct_values()
    {
        $id = Uuid::uuid4();
        $name = 'Test Product';
        $price = 99.99;
        $currency = 'USD';

        $product = new Product($id, $name, $price, $currency);

        $this->assertSame($id, $product->getId());
        $this->assertEquals($name, $product->getName());
        $this->assertEquals($price, $product->getPrice());
        $this->assertEquals($currency, $product->getCurrency());
    }
}
