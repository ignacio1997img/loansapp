<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
    
        'debt',
        'amount',
        'number',
        'date',

        'late',
        'lateN',

        'register_userId',
        'register_agentType',

        'deleted_at',
        'deleted_userId',
        'deleted_agentType'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
