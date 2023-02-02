<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderStatusContractAwarded extends Model
{
    use HasFactory;
    protected $fillable=["bidid","competitorId","contactAwardedDate","document","description","created_userid","edited_userid"];
}
