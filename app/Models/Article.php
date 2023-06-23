<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable =[
        'modelGarment_id',
        'brandGarment_id',
        'categoryGarment_id',
        'name',
        'description',
        'image',
        'deleted_at'
    ];

    public function model()
    {
        return $this->belongsTo(ModelGarment::class, 'modelGarment_id');
    }

    public function category()
    {
        return $this->belongsTo(CategoryGarment::class, 'categoryGarment_id');
    }

    public function marca()
    {
        return $this->belongsTo(BrandGarment::class, 'brandGarment_id');
    }
}
