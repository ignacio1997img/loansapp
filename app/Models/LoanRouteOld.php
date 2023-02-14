<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanRouteOld extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'loan_id',
        'route_id',
        'name',
        'status',
        'register_userId',
        'register_agentType',
        'deleted_at'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'register_userId');
    }
}
