<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'transaction_no',
        'transaction_date',
        'transaction_type',
        'qty',
        'description',
        'created_by',
        'updated_by',
        'is_active',
    ];
}
