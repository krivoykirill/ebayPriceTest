<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Query;
use App\QueryData;

class DemoController extends Controller
{
    public function index() {
        $query=Query::where('id','=','8')->firstOrFail();

        $data=QueryData::where('id','=',$query->query_data_id)->firstOrFail();
        $view = view('show');
        $view->with('query',$query);
        $view->with('data',$data);
        $view->with('demo',true);
        return $view;


    }
}
