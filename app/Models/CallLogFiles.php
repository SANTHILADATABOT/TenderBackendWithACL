<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallLogFiles extends Model
{
    use HasFactory;
    protected $table = 'call_log_files';
    protected $fillable = [
        'bidid',
        'date',
        'refrenceno',
        'randomno',
        'from',
        'to',
        'subject',
        'medium',
        'med_refrenceno',
        'comfile',
        'created_userid',
        'updated_userid'
    ];
}
