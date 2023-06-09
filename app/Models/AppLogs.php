<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppLogs extends Model
{
    use HasFactory;
    protected $fillable = [
        'entry_id', 'action', 'ip_address', 'description', 'url', 'device_details', 'action_by', 'task_title'
    ];
}
