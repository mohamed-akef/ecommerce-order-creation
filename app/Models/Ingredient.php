<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    public function deduct(int $deduct): void
    {
        $newStock = $this->current_quantity - $deduct;

        if ($newStock < 0) {
            throw new \RuntimeException('The current stock not enough');
        }

        $this->current_quantity = $newStock;
    }

    public function increase(int $increase): void
    {
        $this->current_quantity = $this->current_quantity + $increase;
    }
}
