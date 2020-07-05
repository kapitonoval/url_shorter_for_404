<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkRequestLog extends Model
{
    protected $table = 'link_request_log';

    protected $fillable = [
        'link_id',
        'user_ip',
        'user_agent',
        'referrer',
    ];
}
