<?php

namespace Tests\Modules\Invoices\Application\Mappers;

use Tests\TestCase;
use App\Modules\Invoices\Application\Mappers\InvoiceToInvoiceDtoMapper;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Entities\Company;
use App\Modules\Invoices\Domain\Entities\InvoiceLineItem;
use App\Modules\Invoices\Domain\Entities\Product;
use App\Domain\Enums\StatusEnum;
use Ramsey\Uuid\Uuid;

class InvoiceToInvoiceDtoMapperTest extends TestCase
{
    public function test_map_returns_correct_invoice_dto()
    {
        $invoice = $this->createMockInvoice();

        $invoiceDto = InvoiceToInvoiceDtoMapper::map($invoice);

        $this->assertEquals($invoice->getId(), $invoiceDto->id);
        $this->assertEquals($invoice->getNumber(), $invoiceDto->number);
        $this->assertEquals($invoice->getDate(), $invoiceDto->date);
        $this->assertEquals($invoice->getDueDate(), $invoiceDto->dueDate);
        $this->assertEquals($invoice->getStatus(), $invoiceDto->status);
        $this->assertCount(2, $invoiceDto->items);
        $this->assertEquals(300.0, $invoiceDto->totalAmount);
        $this->assertEquals(18.75, $invoiceDto->taxAmount);
        $this->assertEquals(281.25, $invoiceDto->subtotalAmount);
    }

    private function createMockInvoice(): Invoice
    {
        $company = new Company(
            Uuid::uuid4(),
            'Test Company',
            '123 Test St',
            'Test City',
            '12345',
            '555-1234',
            'test@example.com'
        );

        $product1 = new Product(Uuid::uuid4(), 'Product 1', 100.0, 'USD');
        $product2 = new Product(Uuid::uuid4(), 'Product 2', 200.0, 'USD');

        $lineItem1 = new InvoiceLineItem(Uuid::uuid4(), $product1, 1);
        $lineItem2 = new InvoiceLineItem(Uuid::uuid4(), $product2, 1);

        return new Invoice(
            Uuid::uuid4(),
            'INV-123',
            new \DateTimeImmutable(),
            new \DateTimeImmutable('+30 days'),
            $company,
            [$lineItem1, $lineItem2],
            StatusEnum::DRAFT
        );
    }
}
