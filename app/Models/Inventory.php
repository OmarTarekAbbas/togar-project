<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['product_name', 'vendor_name', 'price', 'most_selling', 'rate'];


}
