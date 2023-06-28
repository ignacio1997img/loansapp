<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarmentsMonth extends Model
{
    use HasFactory;

    protected $fillable = [
        'garment_id', 'start', 'finish', 'status', 'amount'
    ];
    public function garment()
    {
        return $this->belongsTo(Garment::class, 'garment_id');
    }
}
