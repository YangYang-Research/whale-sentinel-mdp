<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WsInstance;
use App\Models\WsAgent;

class WSApplication extends Model
{
    use HasFactory;

    protected $table = 'ws_applications';

    protected $fillable = [
        'name',
        'description',
        'language',
        'status',
    ];

    /**
     * Get the instance that owns the application.
     */
    public function instance()
    {
        return $this->belongsTo(WsInstance::class, 'instance_id');
    }
    
    /**
     * Get the agent that owns the application.
     */
    public function agent()
    {
        return $this->hasOne(WsAgent::class, 'application_id');
    }
}
