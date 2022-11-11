<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRoute extends Model
{
    use HasFactory;
    protected $fillable = [
        'loan_id',
        'agent_id',
        'observation',
        'status',
        'register_userId',
        'register_agentType',
        'deleted_at',
        'deleted_userId',
        'deleted_agentType'
    ];

}
