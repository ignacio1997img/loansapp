<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable =   [
                                'people_id',
                                'agentType_id',
                                'observation',
                                'status',
                                'register_userId',
                                'deleted_userId',
                                'deleted_at'
                            ];

    public function people()
    {
        return $this->belongsTo(People::class, 'people_id');
    }

    public function agentType()
    {
        return $this->belongsTo(AgentType::class, 'agentType_id');
    }
}