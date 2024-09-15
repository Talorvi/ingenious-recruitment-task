<?php

namespace Tests\Modules\Invoices\Application\CommandHandlers;

use App\Modules\Invoices\Application\Commands\RejectInvoiceCommand;
use App\Modules\Invoices\Application\Commands\CommandResult;
use App\Modules\Invoices\Application\Commands\Handlers\RejectInvoiceCommandHandler;
use App\Modules\Invoices\Application\Facades\InvoiceFacadeInterface;
use App\Modules\Invoices\Domain\Exceptions\InvalidInvoiceStatusException;
use Ramsey\Uuid\Uuid;
use Mockery;
use Tests\TestCase;

class RejectInvoiceCommandHandlerTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function test_handle_returns_success_on_rejection()
    {
        $invoiceId = Uuid::uuid4();
        $command = new RejectInvoiceCommand($invoiceId);

        $invoiceFacade = Mockery::mock(InvoiceFacadeInterface::class);
        $invoiceFacade->shouldReceive('rejectInvoice')
            ->with($invoiceId)
            ->andReturn(true);

        $handler = new RejectInvoiceCommandHandler($invoiceFacade);

        $result = $handler->handle($command);

        $this->assertInstanceOf(CommandResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals('Invoice rejected successfully.', $result->message);
    }

    public function test_handle_returns_failure_when_rejection_fails()
    {
        $invoiceId = Uuid::uuid4();
        $command = new RejectInvoiceCommand($invoiceId);

        $invoiceFacade = Mockery::mock(InvoiceFacadeInterface::class);
        $invoiceFacade->shouldReceive('rejectInvoice')
            ->with($invoiceId)
            ->andReturn(false);

        $handler = new RejectInvoiceCommandHandler($invoiceFacade);

        $result = $handler->handle($command);

        $this->assertInstanceOf(CommandResult::class, $result);
        $this->assertFalse($result->success);
        $this->assertEquals('Invoice rejection failed.', $result->message);
    }

    public function test_handle_returns_failure_on_exception()
    {
        $invoiceId = Uuid::uuid4();
        $command = new RejectInvoiceCommand($invoiceId);

        $invoiceFacade = Mockery::mock(InvoiceFacadeInterface::class);
        $invoiceFacade->shouldReceive('rejectInvoice')
            ->with($invoiceId)
            ->andThrow(new InvalidInvoiceStatusException('Invalid status'));

        $handler = new RejectInvoiceCommandHandler($invoiceFacade);

        $result = $handler->handle($command);

        $this->assertInstanceOf(CommandResult::class, $result);
        $this->assertFalse($result->success);
        $this->assertStringContainsString('Error during invoice rejection: Invalid status', $result->message);
    }
}
