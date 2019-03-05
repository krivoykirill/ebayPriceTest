<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Auth;
use App\Query;
use Illuminate\Support\Facades\Auth as AuthFacade;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;



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
        $username = AuthFacade::user()->name;
        $queries=Query::where('username', $username)->get();
        return view('home')->with('queries', $queries);
    }
    public function add() {
        return view('add');
    }
    private function execInBackground($cmd) {
        if (substr(php_uname(), 0, 7) == "Windows"){
            pclose(popen("start /B ". $cmd, "r")); 
        }
        else {
            exec($cmd . " > /dev/null &");  
        }
    } 
    public function new() {
        $query = new Query;
        $query->username=AuthFacade::user()->name;
        $query->keywords = $_POST["keywords"];
        $query->buying_type = $_POST["buying_type"];
        $query->condition = $_POST["condition"];
        $query->categoryId = $_POST["categoryId"];

        ($_POST["productId"]!=null)
        ?$query->productId=$_POST["productId"]
        :$query->productId=null;
        $query->save();
        //$cmd = "python ".app_path()."\http\controllers\Py\statsGenerator.py ".$query->id;
        //shell_exec($cmd);
        //shell_exec("python3 /sajt/topy.py '.$query->id.'");
        //to think about refreshing the page after that

        //return redirect('home');

        $process = new Process('dir D:');
        $process->setTimeout(600);
        $process->setIdleTimeout(60);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        echo $process->getOutput();
    }
    
    public function view($id){
        // !!!!!!!!     to do type   $query->type
        $query=Query::where('id','=',$id)->first();
        if ($query==null) {
            abort(404);
        }
        //echo $query->type_value;
        //$value_encoded=urlencode($query->type_value);
        //$url = 'http://krivoy.co.uk/ebaytest.php?'.$type.'='.$value_encoded;
        //echo $url;
       /* $urlresponse = $this->connectService($url);
        if ($urlresponse["code"]==200) {
            
        }
        else {
            abort(404);
        }


        
       //echo $query;
       */
        $view = view('show');
        $view->with('query',$query);
        return $view;
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
