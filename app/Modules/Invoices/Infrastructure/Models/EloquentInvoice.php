<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EloquentInvoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'id',
        'number',
        'date',
        'due_date',
        'company_id',
        'billed_company_id',
        'status',
    ];

    protected $casts = [
        'date' => 'datetime',
        'due_date' => 'datetime',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function company(): BelongsTo
    {
        return $this->belongsTo(EloquentCompany::class, 'company_id', 'id');
    }

    public function billedCompany(): BelongsTo
    {
        return $this->belongsTo(EloquentCompany::class, 'billed_company_id', 'id');
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(EloquentInvoiceLineItem::class, 'invoice_id', 'id');
    }
}
