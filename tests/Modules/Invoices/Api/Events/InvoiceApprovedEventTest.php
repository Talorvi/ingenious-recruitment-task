<?php

namespace Tests\Modules\Invoices\Api\Events;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Api\Dto\CompanyDto;
use App\Modules\Invoices\Api\Dto\InvoiceDto;
use App\Modules\Invoices\Api\Events\InvoiceApproved;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class InvoiceApprovedEventTest extends TestCase
{
    public function test_event_contains_invoice_dto()
    {
        $invoiceDto = $this->createMockInvoiceDto();

        $event = new InvoiceApproved($invoiceDto);

        $this->assertSame($invoiceDto, $event->approvalDto);
    }

    private function createMockInvoiceDto(): InvoiceDto
    {
        return new InvoiceDto(
            Uuid::uuid4(),
            'INV-123',
            new \DateTimeImmutable(),
            new \DateTimeImmutable('+30 days'),
            $this->createMockCompanyDto(),
            StatusEnum::APPROVED,
            [],
            0.0,
            0.0,
            0.0
        );
    }

    private function createMockCompanyDto(): CompanyDto
    {
        return new CompanyDto(
            Uuid::uuid4()->toString(),
            'Test Company',
            '123 Test St',
            'Test City',
            '12345',
            '555-1234',
            'test@example.com'
        );
    }
}
