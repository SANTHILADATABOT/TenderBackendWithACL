<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderStatusFinancialEvaluations extends Model
{
    use HasFactory;
    protected $fillable = ['bidid','competitorId','unitId','pricePerUnit','created_userid','edited_userid'];
}
