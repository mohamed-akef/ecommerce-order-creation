<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductIngredient extends Model
{
    use HasFactory;

    public const STATUS_RESERVED = 'reserved';
}
