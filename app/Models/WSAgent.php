<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WsApplication;

class WSAgent extends Model
{
    use HasFactory;

    protected $table = 'ws_agents';

    protected $fillable = [
        'name',
        'key',
        'description',
        'profile',
        'instance_id',
    ];

    /**
     * Get the application that owns the agent.
     */
    public function application()
    {
        return $this->belongsTo(WsApplication::class, 'application_id');
    }

}
