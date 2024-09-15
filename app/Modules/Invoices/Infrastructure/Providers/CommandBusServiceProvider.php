<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Providers;

use App\Modules\Invoices\Application\Commands\ApproveInvoiceCommand;
use App\Modules\Invoices\Application\Commands\Bus\CommandBusInterface;
use App\Modules\Invoices\Application\Commands\Bus\IlluminateCommandBus;
use App\Modules\Invoices\Application\Commands\Handlers\ApproveInvoiceCommandHandler;
use App\Modules\Invoices\Application\Commands\Handlers\RejectInvoiceCommandHandler;
use App\Modules\Invoices\Application\Commands\RejectInvoiceCommand;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CommandBusServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(CommandBusInterface::class, IlluminateCommandBus::class);

        /** @var CommandBusInterface $bus */
        $bus = $this->app->make(CommandBusInterface::class);
        $bus->map([
            ApproveInvoiceCommand::class => ApproveInvoiceCommandHandler::class,
            RejectInvoiceCommand::class => RejectInvoiceCommandHandler::class,
        ]);
    }

    /** @return array<class-string> */
    public function provides(): array
    {
        return [
            CommandBusInterface::class,
        ];
    }
}
