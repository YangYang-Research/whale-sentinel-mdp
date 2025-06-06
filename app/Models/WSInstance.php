<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WSApplication;

class WSInstance extends Model
{
    use HasFactory;

    protected $table = 'ws_instances';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];
    
    /**
     * Get the applications for the instance.
     */
    public function applications()
    {
        return $this->hasMany(WsApplication::class, 'instance_id');
    }

}
