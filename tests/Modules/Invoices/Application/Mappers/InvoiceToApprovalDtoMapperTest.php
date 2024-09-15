<?php

namespace Tests\Modules\Invoices\Application\Mappers;

use App\Modules\Invoices\Domain\Entities\Company;
use Tests\TestCase;
use App\Modules\Invoices\Application\Mappers\InvoiceToApprovalDtoMapper;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Domain\Enums\StatusEnum;
use Ramsey\Uuid\Uuid;

class InvoiceToApprovalDtoMapperTest extends TestCase
{
    public function test_map_returns_correct_approval_dto()
    {
        $invoice = $this->createMockInvoice();

        $approvalDto = InvoiceToApprovalDtoMapper::map($invoice);

        $this->assertEquals($invoice->getId(), $approvalDto->id);
        $this->assertEquals($invoice->getStatus(), $approvalDto->status);
        $this->assertEquals('invoice', $approvalDto->entity);
    }

    private function createMockInvoice(): Invoice
    {
        return new Invoice(
            Uuid::uuid4(),
            'INV-123',
            new \DateTimeImmutable(),
            new \DateTimeImmutable('+30 days'),
            $this->createMockCompany(),
            $this->createMockBilledCompany(),
            [],
            StatusEnum::DRAFT
        );
    }

    private function createMockCompany(): Company
    {
        return new Company(
            Uuid::uuid4(),
            'Test Company',
            '123 Test St',
            'Test City',
            '12345',
            '555-1234',
            'test@example.com'
        );
    }

    private function createMockBilledCompany(): Company
    {
        return new Company(
            Uuid::uuid4(),
            'Billed Company',
            '456 Billed St',
            'Billing City',
            '67890',
            '555-5678',
            'billing@example.com'
        );
    }
}
