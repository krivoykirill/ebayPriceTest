<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Auth;
use App\Query;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**  
    
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     
    */
    public function index()
    {
        //!!!!!!!!     fake user. will be changed
        $username = 'loproom';
        $queries=Query::where('username', $username)->get();
        return view('home')->with('queries', $queries);
    }
    public function add() {
        return view('add');
    }
    public function new() {
        $query = new Query;
        //!!!!!!!!     fake user. will be changed
        $query->username='loproom';
        $query->type = $_POST["search_type"];
        $query->type_value= $_POST["keywords"];
        $query->save();
        return redirect('home');
        //print_r($_POST);
    }
    public function view($id){
        // !!!!!!!!     to do type   $query->type
        $type = 'keywords';
        $query=Query::where('id','=',$id)->first();
        echo $query->type_value;

        $url = 'http://krivoy.co.uk/ebaytest.php?'.$type.'='.$query->type_value;
        urlencode($url);
        $response = $this->connectService($url);
        if ($response["code"]==200) {
            $view = view('show');
            $view->with('query', $query);
            $view->with('response',$response);
            return $view;
        }
        else {
            abort(404);
        }


        
       //echo $query;
    }


    private function connectService($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec ($ch);
        $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close ($ch);
        $return= ['code'=>404];
        $return = [
            'code'=> $code,
            'response'=> $response
        ];
        return $return;
    }
}
