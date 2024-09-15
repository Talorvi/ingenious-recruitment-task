<?php

namespace Tests\Modules\Invoices\Infrastructure\Repositories;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Domain\Entities\Company;
use App\Modules\Invoices\Infrastructure\Models\EloquentCompany;
use Faker\Factory;
use Tests\TestCase;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use App\Modules\Invoices\Infrastructure\Models\EloquentInvoice;
use App\Modules\Invoices\Infrastructure\Repositories\EloquentInvoiceRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;

class EloquentInvoiceRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private InvoiceRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentInvoiceRepository();
    }

    public function test_find_returns_invoice_when_exists()
    {
        $invoice = $this->createInvoiceInDatabase();

        $foundInvoice = $this->repository->find($invoice->getId());

        $this->assertInstanceOf(Invoice::class, $foundInvoice);
        $this->assertEquals($invoice->getId(), $foundInvoice->getId());
    }

    public function test_find_returns_null_when_not_found()
    {
        $foundInvoice = $this->repository->find(Uuid::uuid4());

        $this->assertNull($foundInvoice);
    }

    public function test_save_persists_new_invoice()
    {
        $invoice = $this->createMockInvoice();

        $this->repository->save($invoice);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->getId()->toString(),
            'number' => $invoice->getNumber(),
            'company_id' => $invoice->getCompany()->getId(),
            'billed_company_id' => $invoice->getBilledCompany()->getId(),
        ]);
    }

    public function test_save_updates_existing_invoice()
    {
        $invoice = $this->createInvoiceInDatabase();

        $invoice->approve();

        $this->repository->save($invoice);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->getId()->toString(),
            'status' => $invoice->getStatus()->value,
        ]);
    }

    public function test_delete_removes_invoice()
    {
        $invoice = $this->createInvoiceInDatabase();

        $this->repository->delete($invoice);

        $this->assertDatabaseMissing('invoices', [
            'id' => $invoice->getId()->toString(),
        ]);
    }

    private function createInvoiceInDatabase(): Invoice
    {
        $company = $this->createMockEloquentCompany();
        $billedCompany = $this->createMockEloquentCompany();
        $eloquentInvoice = EloquentInvoice::create([
            'id' => Uuid::uuid4()->toString(),
            'number' => Uuid::uuid4()->toString(),
            'date' => new \DateTimeImmutable(),
            'due_date' => new \DateTimeImmutable('+30 days'),
            'company_id' => $company->id,
            'billed_company_id' => $billedCompany->id,
            'status' => StatusEnum::DRAFT,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $this->repository->find(Uuid::fromString($eloquentInvoice->id));
    }

    private function createMockInvoice(): Invoice
    {
        $company = $this->createMockEloquentCompany();
        $billedCompany = $this->createMockEloquentCompany();

        return new Invoice(
            Uuid::uuid4(),
            'INV-456',
            new \DateTimeImmutable(),
            new \DateTimeImmutable('+30 days'),
            new Company(
                Uuid::fromString($company->id),
                $company->name,
                $company->street,
                $company->city,
                $company->zip,
                $company->phone,
                $company->email
            ),
            new Company(
                Uuid::fromString($billedCompany->id),
                $billedCompany->name,
                $billedCompany->street,
                $billedCompany->city,
                $billedCompany->zip,
                $billedCompany->phone,
                $billedCompany->email
            ),
            [],
            StatusEnum::DRAFT
        );
    }

    private function createMockEloquentCompany(): EloquentCompany
    {
        $faker = Factory::create();
        return EloquentCompany::create([
            'id' => Uuid::uuid4()->toString(),
            'name' => $faker->company(),
            'street' => $faker->streetAddress(),
            'city' => $faker->city(),
            'zip' => $faker->postcode(),
            'phone' => $faker->phoneNumber(),
            'email' => $faker->safeEmail(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
