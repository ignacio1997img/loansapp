<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    //para la realizacion  de prestamos
    public function ajaxArticle()
    {
        $q = request('q');
        $data = Article::with(['model', 'category','marca'])
            ->whereRaw($q ? '(name like "%'.$q.'%" )' : 1)
            ->where('status', 1)
            ->where('deleted_at', null)->get();

        return response()->json($data);
    }
}
