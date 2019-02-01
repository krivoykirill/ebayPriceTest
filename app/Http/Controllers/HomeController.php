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

    /*
    
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     
    public function sendSms()
    {
      $message ='Your message';
      $url = 'www.your-domain.com/api.php?to='.$mobile.'&text='.$message;
    
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_POST, 0);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
         $response = curl_exec ($ch);
         $err = curl_error($ch);  //if you need
         curl_close ($ch);
         return $response;
    }*/
    public function index()
    {
        $username = 'loproom';
        $queries=Query::where('username', $username)->get();
        return view('home')->with('queries', $queries);
    }
    public function add() {
        return view('add');
    }
    public function new() {
        $query = new Query;
        $query->username='loproom';
        $query->type = $_POST["search_type"];
        $query->type_value= $_POST["keywords"];
        $query->save();
        return redirect('home');
        

        //print_r($_POST);

    }
    public function view($id){

        //to do type   $query->type
        $type = 'keywords';
        $query=Query::where('id',$id)->first();

        $url = 'http://krivoy.co.uk/ebaytest.php?'.$type.'='.$query->type_value;




        $view = view('show');
        $view->with('query', $query);
        return $view;

    }

}
