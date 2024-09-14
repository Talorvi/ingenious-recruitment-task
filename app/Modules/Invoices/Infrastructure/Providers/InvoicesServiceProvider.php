<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Providers;

use App\Modules\Invoices\Application\Facades\InvoiceFacade;
use App\Modules\Invoices\Application\Facades\InvoiceFacadeInterface;
use App\Modules\Invoices\Infrastructure\Repositories\EloquentInvoiceRepository;
use App\Modules\Invoices\Infrastructure\Repositories\InvoiceRepositoryInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class InvoicesServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->scoped(InvoiceFacadeInterface::class, InvoiceFacade::class);
        $this->app->scoped(InvoiceRepositoryInterface::class, EloquentInvoiceRepository::class);
    }

    /** @return array<class-string> */
    public function provides(): array
    {
        return [
            InvoiceFacadeInterface::class,
            InvoiceRepositoryInterface::class,
        ];
    }
}
