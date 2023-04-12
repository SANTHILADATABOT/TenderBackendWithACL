<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallType extends Model
{
    use HasFactory;
    protected $table = 'call_types';
    protected $fillable = ['name','status','created_by'];
}
