<?php

namespace Tests\Modules\Invoices\Application\Services;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Application\Commands\Bus\CommandBusInterface;
use App\Modules\Invoices\Application\Services\InvoiceService;
use App\Modules\Invoices\Domain\Entities\Company;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Exceptions\InvoiceNotFoundException;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Faker\Factory;
use Mockery;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Tests\TestCase;

class InvoiceServiceTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function test_get_invoice_by_id_returns_invoice()
    {
        $invoiceId = Uuid::uuid4();
        $invoice = $this->createMockInvoice($invoiceId);

        $invoiceRepository = Mockery::mock(InvoiceRepositoryInterface::class);
        $invoiceRepository->shouldReceive('find')->with($invoiceId)->andReturn($invoice);

        $service = new InvoiceService($invoiceRepository, Mockery::mock(CommandBusInterface::class));

        $result = $service->getInvoiceById($invoiceId);

        $this->assertSame($invoice, $result);
    }

    public function test_get_invoice_by_id_throws_exception_when_not_found()
    {
        $this->expectException(InvoiceNotFoundException::class);

        $invoiceRepository = Mockery::mock(InvoiceRepositoryInterface::class);
        $invoiceRepository->shouldReceive('find')->andReturn(null);

        $service = new InvoiceService($invoiceRepository, Mockery::mock(CommandBusInterface::class));

        $service->getInvoiceById(Uuid::uuid4());
    }

    private function createMockInvoice(UuidInterface $invoiceId): Invoice
    {
        return new Invoice(
            $invoiceId,
            'INV-123',
            new \DateTimeImmutable(),
            new \DateTimeImmutable('+30 days'),
            $this->createMockCompany(),
            $this->createMockCompany(),
            [],
            StatusEnum::DRAFT
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
