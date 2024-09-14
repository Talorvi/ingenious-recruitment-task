<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Commands\Handlers;

use App\Modules\Invoices\Application\Commands\CommandResult;
use App\Modules\Invoices\Application\Commands\RejectInvoiceCommand;
use App\Modules\Invoices\Application\Facades\InvoiceFacadeInterface;

class RejectInvoiceCommandHandler
{
    private InvoiceFacadeInterface $invoiceFacade;

    public function __construct(InvoiceFacadeInterface $invoiceFacade)
    {
        $this->invoiceFacade = $invoiceFacade;
    }

    public function handle(RejectInvoiceCommand $command): CommandResult
    {
        try {
            if ($this->invoiceFacade->rejectInvoice($command->invoiceId)) {
                return CommandResult::success('Invoice rejected successfully.');
            } else {
                return CommandResult::failure('Invoice rejection failed.');
            }
        } catch (\Exception $e) {
            return CommandResult::failure('Error during invoice rejection: ' . $e->getMessage());
        }
    }
}
