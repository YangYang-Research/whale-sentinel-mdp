<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WSAgent extends Model
{
    use HasFactory;

    protected $table = 'ws_agents';

    protected $fillable = [
        'name',
        'description',
        'profile',
        'application_id',
    ];

    /**
     * Get the instance that owns the agent.
     */
    public function instance()
    {
        return $this->belongsTo(WSInstance::class);
    }
}
