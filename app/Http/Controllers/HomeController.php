<?php namespace App\Http\Controllers;

use App\Repositories\EmailRepository;
use App\Repositories\PayzaRepository;
use App\Repositories\WebsiteRepository;
use Illuminate\Http\Request;
use App\User;
use Validator;
use App\WriterCategory;
use Auth;
use URL;
class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('web');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->middleware('auth');
		return redirect("order");
	}

	public function register(Request $request){
		if($request->method()=='POST'){
			if(!$request->isXmlHttpRequest()){
				$robotChecker = new CaptchaRepository();
				$not_robot = $robotChecker->checkRobot($request);
				if(!$not_robot){
					return redirect("user/register")
							->withErrors([
									'robot'=>"Hey, $request->name. You Are a suspected Robot!"
							])
							->withInput();
				}
			}
			$emailer = new EmailRepository();
			$websiteRepo = new WebsiteRepository();
			$website_id = $websiteRepo->getWebsiteId();
			$exists = User::where([
					['website_id','=',$website_id],
					['email','=',$request->email]
			])->get();
			$validator= Validator::make($request->all(),[
					'name' => 'required|max:255',
					'phone' => 'required|max:18',
					'email' => 'required|email|max:255',
					'password' => 'required|confirmed|min:6',
			]);
			if(count($exists)>0){
				$validator= Validator::make($request->all(),[
						'name' => 'required|max:255',
						'phone' => 'required|max:18',
						'email' => 'required|email|unique:users|max:255',
						'password' => 'required|confirmed|min:6',
				]);

			}
			if ($validator->fails()) {
				if($request->isXmlHttpRequest()){
					return $validator->errors();
				}
				return redirect("user/register")
						->withErrors($validator)
						->withInput();
			}
			$writer_category = WriterCategory::where('deleted','=',0)->orderBy('cpp','asc')->first();
			$user = User::create([
					'name'=>$request->name,
					'email'=>$request->email,
					'phone'=>$request->phone,
					'layout'=>env('LAYOUT','gentella'),
					'role'=>'client',
					'website_id'=>$website_id,
					'country'=>$request->country,
					'password'=>bcrypt($request->password)
			]);
			$user->writer_category_id = $writer_category->id;
            $user->referred_by = round($request->referred_by,0);
			$user->save();
			Auth::login($user);
			$emailer->sendRegistrationEmail($user);
			if($request->isXmlHttpRequest()){
				return $user;
			}
			return redirect('order')->with('notice',['class'=>'success','message'=>'Registration successful']);
		}
		return view('auth.register',[

		]);
	}

	public function login(Request $request){
			$password = $request->password;
        $webRepo = new WebsiteRepository();
        $website_id = $webRepo->getWebsiteId();
        $email = $request->email;
           if(filter_var($email,FILTER_VALIDATE_EMAIL)){
            if (Auth::attempt(['email' => $email, 'password' => $password,'website_id'=>$website_id], false)) {
                $user = Auth::user();
                $response = ['status' => true,'url'=>URL::to(env('HOME','stud')),'name'=>$user->name];
            } else {
                $response = ['status' => false, 'error' => 'Invalid email or password'];
            }
        }else{
            if (Auth::attempt(['username' => $email, 'password' => $password,'website_id'=>$website_id], false)) {
                $response = ['status' => true,'url'=>URL::to(env('HOME','stud'))];
                $user = Auth::user();
            } else {
                $response = ['status' => false, 'error' => 'Invalid email or password'];
            }
        }
        return $response;
	}

	public function payzaIpn(Request $request){
	    $payza = new PayzaRepository();
        $token = $request->token;
        $response = $payza->confirmCode($token);
        return $response;
    }
}
