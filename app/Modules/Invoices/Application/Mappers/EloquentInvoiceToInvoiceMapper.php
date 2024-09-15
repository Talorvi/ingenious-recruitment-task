<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Mappers;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Domain\Entities\Company;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Entities\InvoiceLineItem;
use App\Modules\Invoices\Domain\Entities\Product;
use App\Modules\Invoices\Infrastructure\Models\EloquentCompany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class EloquentInvoiceToInvoiceMapper
{
    public static function map(Model $eloquentInvoice): Invoice
    {
        $company = self::mapCompany($eloquentInvoice->company);
        $lineItems = self::mapLineItems($eloquentInvoice->lineItems);

        return new Invoice(
            id: Uuid::fromString($eloquentInvoice->id),
            number: $eloquentInvoice->number,
            date: $eloquentInvoice->date,
            dueDate: $eloquentInvoice->due_date,
            company: $company,
            lineItems: $lineItems,
            status: StatusEnum::tryFrom($eloquentInvoice->status),
        );
    }

    private static function mapCompany(EloquentCompany $eloquentCompany): Company
    {
        return new Company(
            id: Uuid::fromString($eloquentCompany->id),
            name: $eloquentCompany->name,
            street: $eloquentCompany->street,
            city: $eloquentCompany->city,
            zip: $eloquentCompany->zip,
            phone: $eloquentCompany->phone,
            email: $eloquentCompany->email
        );
    }

    private static function mapLineItems(Collection $eloquentLineItems): array
    {
        $lineItems = [];
        foreach ($eloquentLineItems as $item) {
            $product = new Product(
                id: Uuid::fromString($item->product->id),
                name: $item->product->name,
                price: $item->product->price,
                currency: $item->product->currency
            );

            $lineItems[] = new InvoiceLineItem(
                id: Uuid::fromString($item->id),
                product: $product,
                quantity: $item->quantity
            );
        }
        return $lineItems;
    }
}
