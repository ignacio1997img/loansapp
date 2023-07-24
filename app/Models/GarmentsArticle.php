<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarmentsArticle extends Model
{
    use HasFactory;


    protected $fillable = [
        'garment_id',
        'category_id',
        'category',
        'amountSubTotal',
        'deleted_at'
    ];



    public function garmentArticleDetail()
    {
        return $this->hasMany(GarmentsArticlesDetail::class, 'garmentArticle_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
