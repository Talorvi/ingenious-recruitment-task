<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Exceptions;

use Exception;
use Throwable;

class InvoiceNotFoundException extends Exception
{
    public function __construct(string $message = "Invoice not found", int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
