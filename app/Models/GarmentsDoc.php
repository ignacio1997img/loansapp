<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarmentsDoc extends Model
{
    use HasFactory;

    protected $fillable = [
        'garment_id',
        'image',
        'register_userId',
        'register_agentType',
        'deleted_userId',
        'deleted_agentType',
        'deleteObservation',
        'deleted_at'
    ];

    public function garment()
    {
        return $this->belongsTo(Garment::class, 'garment_id');
    }
}
