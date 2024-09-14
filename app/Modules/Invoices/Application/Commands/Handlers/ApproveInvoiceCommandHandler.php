<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Commands\Handlers;

use App\Modules\Invoices\Application\Commands\ApproveInvoiceCommand;
use App\Modules\Invoices\Application\Commands\CommandResult;
use App\Modules\Invoices\Application\Facades\InvoiceFacadeInterface;

class ApproveInvoiceCommandHandler
{
    private InvoiceFacadeInterface $invoiceFacade;

    public function __construct(InvoiceFacadeInterface $invoiceFacade)
    {
        $this->invoiceFacade = $invoiceFacade;
    }

    public function handle(ApproveInvoiceCommand $command): CommandResult
    {
        try {
            if ($this->invoiceFacade->approveInvoice($command->invoiceId)) {
                return CommandResult::success('Invoice approved successfully.');
            } else {
                return CommandResult::failure('Invoice approval failed.');
            }
        } catch (\Exception $e) {
            return CommandResult::failure('Error during invoice approval: ' . $e->getMessage());
        }
    }
}
