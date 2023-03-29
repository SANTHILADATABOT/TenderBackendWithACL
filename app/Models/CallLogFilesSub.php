<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallLogFilesSub extends Model
{
    use HasFactory;
    protected $table = 'call_log_files_subs';
    protected $fillable = [
        'randomno',
        'mainid',
        'comfile',
        'filetype',
        'createdby_userid',
        'updatedby_userid'
    ];
}
