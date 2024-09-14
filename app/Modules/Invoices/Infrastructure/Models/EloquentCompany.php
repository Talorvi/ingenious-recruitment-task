<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentCompany extends Model
{
    protected $table = 'companies';

    protected $fillable = [
        'id',
        'name',
        'street',
        'city',
        'zip',
        'phone',
        'email',
    ];

    public $incrementing = false;
    protected $keyType = 'string';
}
