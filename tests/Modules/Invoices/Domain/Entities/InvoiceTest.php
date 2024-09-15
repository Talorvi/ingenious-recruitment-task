<?php

namespace Tests\Modules\Invoices\Domain\Entities;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Domain\Entities\Company;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Exceptions\InvalidInvoiceStatusException;
use Faker\Factory;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    public function test_invoice_getters_return_expected_values()
    {
        $id = Uuid::uuid4();
        $invoiceNumber = 'INV-123';
        $issueDate = new \DateTimeImmutable();
        $dueDate = new \DateTimeImmutable('+30 days');
        $company = $this->createMockCompany();
        $billedCompany = $this->createMockCompany();
        $items = [
            ['name' => 'Product 1', 'quantity' => 1, 'price' => 100.00],
            ['name' => 'Product 2', 'quantity' => 2, 'price' => 200.00],
        ];
        $status = StatusEnum::DRAFT;

        $invoice = new Invoice(
            $id,
            $invoiceNumber,
            $issueDate,
            $dueDate,
            $company,
            $billedCompany,
            $items,
            $status
        );

        $this->assertEquals($id, $invoice->getId());
        $this->assertEquals($invoiceNumber, $invoice->getNumber());
        $this->assertEquals($issueDate, $invoice->getDate());
        $this->assertEquals($dueDate, $invoice->getDueDate());
        $this->assertEquals($company, $invoice->getCompany());
        $this->assertEquals($items, $invoice->getLineItems());
        $this->assertEquals($status, $invoice->getStatus());
    }

    public function test_can_approve_draft_invoice()
    {
        $invoice = $this->createInvoice(StatusEnum::DRAFT);
        $invoice->approve();
        $this->assertEquals(StatusEnum::APPROVED, $invoice->getStatus());
    }

    public function test_cannot_approve_non_draft_invoice()
    {
        $this->expectException(InvalidInvoiceStatusException::class);
        $invoice = $this->createInvoice(StatusEnum::APPROVED);
        $invoice->approve();
    }

    private function createInvoice(StatusEnum $status): Invoice
    {
        return new Invoice(
            Uuid::uuid4(),
            'INV-123',
            new \DateTimeImmutable(),
            new \DateTimeImmutable('+30 days'),
            $this->createMockCompany(),
            $this->createMockCompany(),
            [],
            $status
        );
    }

    private function createMockCompany(): Company
    {
        $faker = Factory::create();
        return new Company(
            id: Uuid::uuid4(),
            name: $faker->company(),
            street: $faker->streetAddress(),
            city: $faker->city(),
            zip: $faker->postcode(),
            phone: $faker->phoneNumber(),
            email: $faker->safeEmail()
        );
    }
}
