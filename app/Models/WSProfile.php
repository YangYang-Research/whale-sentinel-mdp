<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSProfile extends Model
{
    use HasFactory;

    protected $table = 'ws_profiles';

    // Cho phép mass assignment cho các field sau:
    protected $fillable = [
        'name',
        'description',
        'profile',
    ];
}
