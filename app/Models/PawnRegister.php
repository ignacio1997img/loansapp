<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PawnRegister extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id',
        'person_id',
        'date',
        'date_limit',
        'interest_rate',
        'observations',
        'status'
    ];

    public function person(){
        return $this->belongsTo(People::class, 'person_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details(){
        return $this->hasMany(PawnRegisterDetail::class, 'pawn_register_id');
    }

    public function payments(){
        return $this->hasMany(PawnRegisterPayment::class, 'pawn_register_id');
    }
}
