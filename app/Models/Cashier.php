<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashier extends Model
{
    use HasFactory;

    protected $fillable = [
        'vault_id', 'user_id', 'title', 'observations', 'status', 'closed_at', 'deleted_at'
    ];

    public function movements(){
        return $this->hasMany(CashierMovement::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function vault()
    {
        return $this->belongsTo(Vault::class, 'vault_id');
    }



    public function details(){
        return $this->hasMany(CashierDetail::class);
    }

    public function vault_details(){
        return $this->hasOne(VaultDetail::class, 'cashier_id');
    }

    public function client(){
        return $this->hasMany(Client::class);
    }
}
