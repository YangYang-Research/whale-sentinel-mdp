<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSService extends Model
{
    use HasFactory;

    protected $table = 'ws_services';

    protected $fillable = [
        'name',
        'description',
        'profile',
    ];
}
