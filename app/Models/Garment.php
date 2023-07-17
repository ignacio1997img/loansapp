<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Garment extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'people_id',
        'cashier_id',
        'type',
        'month',
        'monthCant',
        'date',
        'amountLoan',
        'priceDollar',
        'amountLoanDollar',
        'amountPorcentage',
        'porcentage',
        'amountTotal',
        'observation',
        'status',
        'delivered',
        'dateDelivered',
        'delivered_userId',
        'delivered_agentType',
        'success_userId',
        'success_agentType',
        'cashierRegister_id',
        'register_userId',
        'register_agentType',
        'deleted_userId',
        'deleted_agentType',
        'deleteObservation',
        'deletedKey',
        'deleted_at'
    ];

    public function garmentArticle()
    {
        return $this->hasMany(GarmentsArticle::class, 'garment_id');
    }



    //para ver que persona es la que entrega el prestamo al beneficiario
    public function agentDelivered()
    {
        return $this->belongsTo(User::class, 'delivered_userId');
    }

    public function people()
    {
        return $this->belongsTo(People::class, 'people_id');
    }



    public function months()
    {
        return $this->hasMany(GarmentsMonth::class, 'garment_id');
    }



}
