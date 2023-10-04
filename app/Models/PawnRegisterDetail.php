<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PawnRegisterDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'pawn_register_id',
        'item_type_id',
        'price',
        'quantity',
        'observations'
    ];

    public function type(){
        return $this->belongsTo(ItemType::class, 'item_type_id');
    }

    public function features_list(){
        return $this->hasMany(PawnRegisterDetailFeature::class, 'pawn_register_detail_id');
    }
}
