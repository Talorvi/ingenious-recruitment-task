<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentProduct extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'id',
        'name',
        'price',
        'currency',
    ];

    public $incrementing = false;
    protected $keyType = 'string';
}
