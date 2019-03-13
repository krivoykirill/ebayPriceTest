<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Auth;
use App\Query;
use App\QueryData;
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
        shell_exec("python3 ".app_path()."/Http/Controllers/Py/statsGenerator.py ".$query->id." > /dev/null 2>/dev/null &");
        return redirect('home');
    }
    
    public function view($id){
        $query=Query::where('id','=',$id)->firstOrFail();
        $username = AuthFacade::user()->name;
        if($query->username!=$username){
            abort(403);
        }
        $data=QueryData::where('id','=',$query->query_data_id)->firstOrFail();
        $queries=Query::where('username', $username)->get();
        $view = view('show');
        $view->with('query',$query);
        $view->with('data',$data);
        $view->with('queries',$queries);

        return $view;
    }

    public function deleteAll(Request $request){
        $ids = $request->ids;
        foreach ($ids as $id) {
            $query=Query::find($id);
            if($query==null){
                continue;
            }
            $username = AuthFacade::user()->name;
            if($query->username!=$username){
                continue;
            }
            $datas=QueryData::where('id','=',$query->query_data_id)->get();
            if ($datas!=null){
                foreach ($datas as $data){
                    $data->delete();
                }
                $query->delete();
            }
        }
        return response()->json($request);
        //DB::table("products")->whereIn('id',explode(",",$ids))->delete();
        //return response()->json(['success'=>"Products Deleted successfully."]);
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
    public function destroy($id){
        $query=Query::find($id);
        if($query==null){
            abort(404);
        }
        $username = AuthFacade::user()->name;
        if($query->username!=$username){
            abort(403);
        }
        $datas=QueryData::where('id','=',$query->query_data_id)->get();
        if ($datas!=null){
            foreach ($datas as $data){
                $data->delete();
                echo "YAS ".$username;
            }
            $query->delete();
        }
        return redirect('home');
        
    }
}
