<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarmentsArticlesDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'garmentArticle_id',
        'articleDeveloper_id',
        'developer',
        'foreign_id',
        'typeForeign',
        'title',
        'value',
        'deleted_at'
    ];
}
