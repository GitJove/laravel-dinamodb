<?php

namespace App\Http\Controllers;

use App\Eloquent;
use Illuminate\Http\Request;

class EloquentDinamoDb extends Controller
{
    public function index()
    {
        dd(Eloquent::where('count2', '>', 0)->get());
    }

    public function create()
    {
        Eloquent::create([
            'id' => md5(microtime()),
            'id2' => md5(microtime().random_int(0, 1000000)),
            'count' => random_int(0, 100),
            'count2' => random_int(0, 100),
        ]);
    }
}
