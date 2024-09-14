<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Facades;

use App\Modules\Invoices\Api\Dto\InvoiceDto;
use Ramsey\Uuid\UuidInterface;

interface InvoiceFacadeInterface
{
    public function getInvoiceById(UuidInterface $id): ?InvoiceDto;

    public function approveInvoice(UuidInterface $id): bool;

    public function rejectInvoice(UuidInterface $id): bool;
}
