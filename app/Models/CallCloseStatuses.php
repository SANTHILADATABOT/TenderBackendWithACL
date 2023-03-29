<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallCloseStatuses extends Model
{
    use HasFactory;
    protected $table = 'call_close_statuses';
    protected $fillable = [
        'name',
        'active_status',
        'created_userid',
        'edited_userid',
    ];
}
