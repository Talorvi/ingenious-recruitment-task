<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentInvoiceLineItem extends Model
{
    protected $table = 'invoice_product_lines';

    protected $fillable = [
        'id',
        'invoice_id',
        'product_id',
        'quantity',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function product(): BelongsTo
    {
        return $this->belongsTo(EloquentProduct::class, 'product_id', 'id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(EloquentInvoice::class, 'invoice_id', 'id');
    }
}
