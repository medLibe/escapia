<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'item_name',
        'unit_type',
        'packaging',
        'qty_per_packaging',
        'created_by',
        'updated_by',
        'is_active',
    ];
}
