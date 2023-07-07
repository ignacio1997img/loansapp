<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarmentsMonthAgent extends Model
{
    use HasFactory;

    protected $fillable = [
        'deleted_at',
        'garmentMonth_id',
        'transaction_id',
        'cashier_id',
        'amount',
        'agent_id',
        'agentType',
        'status',
        'deleted_userId',
        'deleted_agentType',
        'deleteObservation',
        'deletedKey',

        'garment_id',
        'type'
    ];


    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function garment()
    {
        return $this->belongsTo(Garment::class, 'garment_id');
    }

    public function garmentMonth()
    {
        return $this->belongsTo(GarmentsMonth::class, 'garmentMonth_id');
    }
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
