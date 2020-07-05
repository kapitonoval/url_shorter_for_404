<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    protected $table = 'links';
    protected $fillable = [
        'link',
        'link_hash',
        'link_short_shuffle',
    ];
}
