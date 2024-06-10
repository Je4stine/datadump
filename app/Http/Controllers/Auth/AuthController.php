<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\MenuRepository;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use URL;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/order';

//    protected $redirectAfterLogout;
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(MenuRepository $menuRepository)
    {
        $menuRepository->check();
        $www_url = URL::to('/');
        $ww_array = explode('/',$www_url);
        unset($ww_array[count($ww_array)-1]);
//        $this->redirectAfterLogout = implode('/',$ww_array);
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $webRepo = new WebsiteRepository();
        $website_id = $webRepo->getWebsiteId();
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'website_id'=>$website_id,
            'role'=>'client',
            'layout'=>'gentella'
        ]);
    }
}
