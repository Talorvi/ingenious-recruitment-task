<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Commands;

class CommandResult
{
    public bool $success;
    public ?string $message;

    public function __construct(bool $success, ?string $message = null)
    {
        $this->success = $success;
        $this->message = $message;
    }

    public static function success(?string $message = null): self
    {
        return new self(true, $message);
    }

    public static function failure(?string $message = null): self
    {
        return new self(false, $message);
    }
}
