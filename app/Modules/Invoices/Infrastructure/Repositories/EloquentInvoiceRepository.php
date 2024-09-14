<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Repositories;

use App\Modules\Invoices\Application\Mappers\EloquentInvoiceToInvoiceMapper;
use App\Modules\Invoices\Application\Mappers\InvoiceToEloquentInvoiceMapper;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Infrastructure\Models\EloquentInvoice;
use Ramsey\Uuid\UuidInterface;

class EloquentInvoiceRepository implements InvoiceRepositoryInterface
{
    public function find(UuidInterface $id): ?Invoice
    {
        $eloquentInvoice = EloquentInvoice::with(['company', 'lineItems', 'lineItems.product'])
            ->find($id->toString());

        if ($eloquentInvoice === null) {
            return null;
        }

        return EloquentInvoiceToInvoiceMapper::map($eloquentInvoice);
    }

    public function save(Invoice $invoice): void
    {
        $eloquentInvoice = EloquentInvoice::find($invoice->getId()->toString());

        if (!$eloquentInvoice) {
            $eloquentInvoice = new EloquentInvoice();
        }

        $eloquentInvoice = InvoiceToEloquentInvoiceMapper::map($invoice, $eloquentInvoice);

        $eloquentInvoice->save();
    }

    public function delete(Invoice $invoice): void
    {
        $eloquentInvoice = EloquentInvoice::find($invoice->getId()->toString());

        if ($eloquentInvoice) {
            $eloquentInvoice->delete();
        }
    }
}
