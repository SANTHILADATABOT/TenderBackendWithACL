<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementType extends Model
{
    use HasFactory;
    protected $table = 'procurement_types';
    protected $fillable = ['call_type_id','procurement_type_name','status'];
}
