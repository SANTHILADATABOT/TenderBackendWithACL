<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer_group extends Model
{
    use HasFactory;
    protected $fillable = [
        'state', 'customer_sub_category','smart_city'
    ];
}
