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
        'article_id',
        'categoryGarment_id',
        'brandGarment_id',
        'modelGarment_id',
        'fileCi',
        'article',
        'categoryGarment',
        'brandGarment',
        'modelGarment',
        'articleDescription',
        'cashier_id',
        'type',
        'date',

        'amountLoan',
        'amountLoanDollar',
        'priceDollar',
        'amountPorcentage',
        'porcentage',
        'amountTotal',
        'month',
        'monthCant',
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

    //para ver que persona es la que entrega el prestamo al beneficiario
    public function agentDelivered()
    {
        return $this->belongsTo(User::class, 'delivered_userId');
    }

    public function people()
    {
        return $this->belongsTo(People::class, 'people_id');
    }

    public function doc()
    {
        return $this->hasMany(GarmentsDoc::class, 'garment_id');
    }

    public function image()
    {
        return $this->hasMany(GarmentsImage::class, 'garment_id');
    }

    public function months()
    {
        return $this->hasMany(GarmentsMonth::class, 'garment_id');
    }



}
