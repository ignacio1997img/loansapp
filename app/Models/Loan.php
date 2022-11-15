<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'people_id',
        'guarantor_id',
        'cashier_id',
        
        'typeLoan',
        'date',
        'day',
        'month',
        'observation',

        'porcentage',
        'amountLoan',
        'debt',
        'amountPorcentage',
        'amountTotal',
        'status',
        'delivered',
        'dateDelivered',
        
        'inspector_userId',
        'inspector_agentType',

        'success_userId',
        'success_agentType',
        
        'register_userId',
        'register_agentType',

        'deleted_at',

        'deleted_userId',
        'deleted_agentType',
    ];


    public function people()
    {
        return $this->belongsTo(People::class, 'people_id');
    }

    public function loanDay()
    {
        return $this->hasMany(LoanDay::class);
    }

    public function loanRoute()
    {
        return $this->hasMany(LoanRoute::class);
    }

    public function loanRequirement()
    {
        return $this->hasMany(LoanRequirement::class);
    }
}
