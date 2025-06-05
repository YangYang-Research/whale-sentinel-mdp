<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WSAgent;

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
     * Get the agents for the instance.
     */
    public function agents()
    {
        return $this->hasMany(WSAgent::class);
    }
}
