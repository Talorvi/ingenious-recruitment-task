<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Repositories;

use App\Modules\Invoices\Domain\Entities\Invoice;
use Ramsey\Uuid\UuidInterface;

interface InvoiceRepositoryInterface
{
    public function find(UuidInterface $id): ?Invoice;
    public function save(Invoice $invoice): void;
    public function delete(Invoice $invoice): void;
}
