<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Mappers;

use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Infrastructure\Models\EloquentInvoice;

class InvoiceToEloquentInvoiceMapper
{
    public static function map(Invoice $invoice, EloquentInvoice $eloquentInvoice): ?EloquentInvoice
    {
        $eloquentInvoice->id = $invoice->getId()->toString();
        $eloquentInvoice->number = $invoice->getNumber();
        $eloquentInvoice->date = $invoice->getDate();
        $eloquentInvoice->due_date = $invoice->getDueDate();
        $eloquentInvoice->status = $invoice->getStatus()->value;
        $eloquentInvoice->company_id = $invoice->getCompany()->getId()->toString();
        $eloquentInvoice->billed_company_id = $invoice->getBilledCompany()->getId()->toString();

        return $eloquentInvoice;
    }
}
