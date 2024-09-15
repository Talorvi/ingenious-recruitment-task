<?php

namespace Tests\Modules\Invoices\Domain\Exceptions;

use App\Modules\Invoices\Domain\Exceptions\InvoiceNotFoundException;
use Tests\TestCase;

class InvoiceNotFoundExceptionTest extends TestCase
{
    public function test_exception_has_correct_message_and_code()
    {
        $message = 'Invoice not found';
        $code = 404;

        $exception = new InvoiceNotFoundException();

        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
    }

    public function test_exception_can_set_custom_message_and_code()
    {
        $message = 'Custom not found message';
        $code = 404;

        $exception = new InvoiceNotFoundException($message, $code);

        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
    }
}
