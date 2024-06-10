<?php

namespace App\Http\Controllers;

use App\Academic;
use App\AdminGroup;
use App\Device;
use App\File;
use App\Phone;
use App\Repositories\CaptchaRepository;
use App\Repositories\EmailRepository;
use App\Repositories\FileSaverRepository;
use App\Repositories\MenuRepository;
use App\Repositories\PaypalRepository;
use App\Repositories\WebsiteRepository;
use App\Style;
use App\Subject;
use App\UserTrait;
use App\Website;
use App\WriterCategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Validator;
use App\User;
use URL;

class UserController extends Controller
{
    //
    protected $user;
    protected $active_status = 0;
    protected $available_status = 0;
    protected $revision_status = 2;
    protected $active_bids = 0;
    protected $completed_status = 4;
    protected $pending_status = 3;
    protected $cancelled_status = 7;
    protected $emailer;
    protected $website;
    public function __construct(Request $request)
    {
        new MenuRepository($request);
        $this->emailer = new EmailRepository();
        $this->user = Auth::user();
        $web_repo = new WebsiteRepository();
        $this->website = $web_repo->getWebsite();
    }

    public function profile(Request $request)
    {
        $method = $request->method();
        $user = $this->user;
        if ($method == 'POST') {
            $data = array();
            $user->phone = $request->phone;
            $user->update();
            if ($request->password) {
                $this->validate($request, [
                    'name' => 'required|max:255',
                    'phone' => 'required',
                    'password' => 'required|confirmed|min:4',

                ]);
                $user->password = bcrypt($request->password);
            } else {
                $this->validate($request, [
                    'name' => 'required|max:255',
                    'phone' => 'required',
                    'country' => 'required'
                ]);
            }
            if ($request->username != $this->user->username) {
                $this->validate($request, [
                    'name' => 'required|max:255',
                    'phone' => 'required',
                    'username' => 'unique:users'
                ]);
            }
            $user->name = $request->name;
            $user->username = $request->username;
            $user->phone = $request->phone;
            $user->country = $request->country;
            $user->update();
            return redirect('user/profile')->with('notice', ['class' => 'success', 'message' => 'Profile Updated']);
        }
        if ($method == 'PUT') {
            $file = $request->file('image');
            $real_path = @$file->getRealPath();
            if (!getimagesize($real_path)) {
                return redirect('user/profile')->with('notice', ['class' => 'error', 'message' => 'Invalid Image!']);
            }
            $filename = $file->getClientOriginalName();
            $year = date('Y');
            $month = date('M');
            $day = date('d');
            $relative_path = '/uploads/' . $year . '/' . $month . '/' . $day;
            $directory = public_path() . $relative_path;
            $new_name = strtotime(date('h:i:s')) . '_' . str_replace(' ', '_', $filename);
            $path = $relative_path . '/' . $new_name;
            if ($request->file('image')->move($directory, $new_name)) {
                $user->image = '' . $path;
                $user->update();
                return redirect('user/profile')->with('notice', ['class' => 'success', 'message' => 'Profile Pic Updated']);
            } else {
                return redirect('user/profile')->with('notice', ['class' => 'error', 'message' => 'An unexpected error occurred while uploading image. Please retry!']);
            }
        }
        return view('user.profile', []);
    }

    public function users($role, Request $request)
    {
        $client_websites = Website::where('author', 0)->lists('id');
        $key = $request->search;
        if ($request->search) {
            if (filter_var($key, FILTER_VALIDATE_EMAIL)) {
                if ($role == 'writer') {
                    $users = User::where([
                        ['role', 'LIKE', $role],
                        ['status', '=', 1],
                        ['suspended', '=', 0],
                        ['email', 'like', "%$key%"]
                    ])->orderBy('id', 'desc')->paginate(10);
                } elseif ($role == 'author') {
                    $users = User::where([
                        ['role', 'LIKE', 'client'],
                        ['email', 'like', "%$key%"]
                    ])->whereNotIn('website_id', $client_websites)
                        ->orderBy('id', 'desc')->paginate(10);
                } else {
                    $users = User::where([
                        ['role', 'like', $role],
                        ['status', '=', 1],
                        ['email', 'like', "%$key%"]
                    ])
                        ->whereIn('website_id', $client_websites)
                        ->orderBy('created_at', 'desc')->paginate(10);
                }
            } else {
                if (is_numeric($key)) {
                    if ($role == 'writer') {
                        $users = User::where([
                            ['role', 'LIKE', $role],
                            ['status', '=', 1],
                            ['suspended', '=', 0],
                            ['id', '=', "$key"]
                        ])->orderBy('id', 'desc')->paginate(10);
                    } elseif ($role == 'authors') {
                        $users = User::where([
                            ['role', 'LIKE', 'client'],
                            ['id', '=', "$key"]
                        ])->whereNotIn('website_id', $client_websites)
                            ->orderBy('id', 'desc')->paginate(10);
                    } else {
                        $users = User::where([
                            ['role', 'like', $role],
                            ['id', '=', "$key"]
                        ])
                            ->whereIn('website_id', $client_websites)
                            ->orderBy('created_at', 'desc')->paginate(10);
                    }
                } else {
                    if ($role == 'writer') {
                        $users = User::where([
                            ['role', 'LIKE', $role],
                            ['status', '=', 1],
                            ['suspended', '=', 0],
                            ['name', 'like', "%$key%"]
                        ])->orderBy('id', 'desc')->paginate(10);
                    } else if ($role == 'authors') {
                        $users = User::where([
                            ['role', 'like', 'author'],
                            ['name', 'like', "%$key%"]
                        ])->whereNotIn('website_id', $client_websites)
                            ->orderBy('created_at', 'desc')->paginate(10);
                    } else {
                        $users = User::where([
                            ['role', 'like', $role],
                            ['name', 'like', "%$key%"]
                        ])->whereIn('website_id', $client_websites)
                            ->orderBy('created_at', 'desc')->paginate(10);
                    }
                }
            }
        } else {
            if ($role == 'writer') {
                $users = User::where([
                    ['role', 'LIKE', $role],
                    ['status', '=', 1],
                    ['suspended', '=', 0]
                ])->orderBy('id', 'desc')->paginate(10);
            } elseif ($role == 'authors') {
                $users = User::where([
                    ['role', 'LIKE', 'client'],
                ])->whereNotIn('website_id', $client_websites)
                    ->orderBy('id', 'desc')->paginate(10);
            } else {
                $users = User::where(['role' => $role])->whereIn('website_id', $client_websites)->orderBy('created_at', 'desc')->paginate(10);
            }
        }
        if ($role == 'authors') {
            $role = 'client';
        }



        return view('user.' . $role, [
            'users' => $users,
            'role' => $role
        ]);
    }

    /**
     * @param $role
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * View a single user
     */
    public function viewSingle($role, User $user)
    {
        return view('user.single', [
            'user' => $user
        ]);
    }


    public function addTrait(User $user, Request $request, $trait = null)
    {
        $trait = UserTrait::findOrNew($trait);
        if ($request->isMethod('post')) {
            $user->traits()->updateOrCreate(['id' => $request->id], [
                'trait' => $request->trait,
                'description' => $request->description
            ]);
            return redirect("user/view/$user->role/$user->id")->with('notice', ['class' => 'success', 'message' => 'Trait saved']);
        }
        return view('user.add_trait', [
            'user' => $user,
            'trait' => $trait
        ]);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     *
     * Change a user role
     */

    public function changeRole(User $user, Request $request)
    {
        if ($user == $this->user) {
            return redirect("user/view/$user->role")->with('notice', ['class' => 'error', 'message' => 'You cannot change your own role!!']);
        } else {
            $method = $request->method();
            if ($method == 'POST') {
                $user->role = $request->role;
                $user->admin_group_id = 0;
                if ($request->role == 'admin') {
                    $user->admin_group_id = $request->admin_group_id;
                    $user->update();
                    return redirect("admin_groups/view/$user->admin_group_id")->with('notice', ['class' => 'info', 'message' => 'User role changed successfully']);
                }
                if ($request->role == 'writer') {
                    $writer_category = WriterCategory::where('deleted', '=', 0)->orderBy('amount', 'asc')->first();
                    $user->writer_category_id = $writer_category->id;
                }
                $user->update();
                return redirect("user/view/$user->role")->with('notice', ['class' => 'info', 'message' => 'User role changed successfully']);
            }
        }
        $admin_groups = AdminGroup::get();
        return view('user.change_role', [
            'user' => $user,
            'admin_groups' => $admin_groups
        ]);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * View user orders
     */
    public function orders(User $user)
    {
        $orders  = $user->orders()->orderBy('deadline')->paginate(10);
        return view('user.orders', [
            'orders' => $orders,
            'user' => $user
        ]);
    }

    /**
     * User login using get from wordpress or post from system
     */
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $webRepo = new WebsiteRepository();
        $website_id = $webRepo->getWebsiteId();
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (Auth::attempt(['email' => $email, 'password' => $password, 'website_id' => $website_id], false)) {

                $user = Auth::user();
                $response = ['status' => true, 'url' => URL::to(env('HOME', 'stud')), 'name' => $user->name];
                $this->notifyLoginSuccess($user, $request);
            } else {
                $response = ['status' => false, 'error' => 'Invalid email or password'];
                $this->notifyLoginFail($email, $request);
            }
        } else {
            if (Auth::attempt(['username' => $email, 'password' => $password, 'website_id' => $website_id], false)) {
                $response = ['status' => true, 'url' => URL::to(env('HOME', 'stud'))];
                $user = Auth::user();
                //$this->notifyLoginSuccess($user,$request);
            } else {
                $response = ['status' => false, 'error' => 'Invalid email or password'];
                //$this->notifyLoginFail($email,$request);

            }
        }

        if ($request->isXmlHttpRequest()) {
            echo json_encode($response);
        } else {
            if ($response['status']) {
                return redirect(env('HOME'))->with('notice', ['class' => 'info', 'message' => 'Welcome back to your order management panel']);
            } else {
                return redirect('login')->withErrors([
                    'email' => 'Invalid email or password'
                ]);
            }
        }
    }

    function notifyLoginSuccess($user, Request $request)
    {
        $ip = $request->ip();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $known_device = $user->devices()->where([
            ['ip_address', '=', $ip],
            ['HTTP_USER_AGENT', 'like', $user_agent]
        ])->first();
        if (!$known_device) {
            $url = "http://www.geoplugin.net/json.gp?ip=$ip";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POSTFIELDS, null);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Accept: application/json'
            ));
            $content = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $json_response = null;
            if ($status == 200 || $status == 201) {
                $json_response = json_decode($content);
                $user->devices()->create([
                    'ip_address' => $ip,
                    'HTTP_USER_AGENT' => $user_agent,
                    'country' => $json_response->geoplugin_countryName
                ]);
                $emailer = new EmailRepository();
                $message = 'Hello ' . $user->name . '(' . $user->email . '),<br/>
                <strong>A new Device was used to sign in to your account </strong><br/>
                <strong><u>More Details..</u></strong>
                <table border="1">
                    <tr>
                        <th>IP</th>
                        <td>' . $ip . '</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>' . $json_response->geoplugin_countryName . '</td>
                    </tr>
                    <tr>
                        <th>Device</th>
                        <td>' . $user_agent . '</td>
                    </tr>
                </table>
                <p style="color: red;">If this was not you, please change your password to a more secure one as soon as possible</p>
              ';
                // $emailer->sendMail($user->email,"New Device Sign in",$message);
            }
        } else {
            $known_device->ip_address = $ip;
            $known_device->HTTP_USER_AGENT = $user_agent;
            $known_device->created_at = date('Y-m-d H:i:s');
            $known_device->save();
        }
    }
    function notifyLoginFail($email, $request)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $user = User::where([
                ['email', 'like', $email],
                ['website_id', '=', $this->website->id]
            ])->first();
        } else {
            $user = User::where([
                ['username', 'like', $email],
                ['website_id', '=', $this->website->id]
            ])->first();
        }
        if (isset($user)) {
            $ip = $request->ip();
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $url = "http://www.geoplugin.net/json.gp?ip=$ip";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POSTFIELDS, null);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Accept: application/json'
            ));
            $content = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $json_response = null;
            if ($status == 200 || $status == 201) {
                $json_response = json_decode($content);
                $emailer = new EmailRepository();
                $message = 'Hello ' . $user->name . '(' . $user->email . '),<br/>
                <strong><strong style="color:red;">Someone attempted to Login into your account!! </strong><br/>
                <strong><u>More Details..</u></strong>
                <table border="1">
                    <tr>
                        <th>IP</th>
                        <td>' . $ip . '</td>
                    </tr>
                    <tr>
                        <th>Device</th>
                        <td>' . $user_agent . '</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>' . $json_response->geoplugin_countryName . '</td>
                    </tr>
                    <tr>
                        <th>Region</th>
                        <td>' . $json_response->geoplugin_region . '</td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td>' . $json_response->geoplugin_city . '</td>
                    </tr>
                </table>
              ';
                //  $emailer->sendMail($user->email,"Login Attempt",$message);
            }
        }
    }

    /**
     * add a new admin
     */

    public function addUser($role, Request $request)
    {
        //        exit;
        if ($request->method() == 'POST') {
            $webRepo = new WebsiteRepository();
            $website_id = $webRepo->getWebsiteId();
            $exists = User::where([
                ['website_id', '=', $website_id],
                ['email', '=', $request->email]
            ])->get();
            $ip_api = "http://www.geoplugin.net/json.gp?ip=154.77.123.44";
            //        $ip_api = "http://www.geoplugin.net/json.gp?ip=".$request->ip();
            $country = json_decode(file_get_contents($ip_api))->geoplugin_countryName;
            $request->country = $country;
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'phone' => 'required|max:18',
                'email' => 'required|email|max:255',
                'password' => 'required|confirmed|min:6',
                'country' => $country
            ]);
            if (count($exists) > 0) {
                return redirect("user/add/admin")
                    ->withErrors([
                        'email' => 'Email has already been used'
                    ])
                    ->withInput();
            }
            if ($validator->fails()) {
                return redirect("user/add/admin")
                    ->withErrors($validator)
                    ->withInput();
            }
            $emailer = new EmailRepository();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'layout' => env('LAYOUT', 'gentella'),
                'role' => $role,
                'website_id' => $website_id,
                'country' => $country,
                'password' => bcrypt($request->password)
            ]);



            return redirect('user/view/admin')->with('notice', ['class' => 'success', 'message' => 'User added successfully']);
        }
        return view('auth.register', []);
    }

    /**
     * Show writer payments
     */

    public function payments(User $user)
    {
        $assigns =  $user->assigns()
            ->join('orders', 'orders.id', '=', 'assigns.order_id')
            ->whereIn('assigns.status', [$this->pending_status, $this->completed_status])
            ->select('assigns.*', 'orders.status as order_status', 'orders.topic', 'orders.pages')
            ->orderBy('assigns.id', 'desc')
            ->paginate(10);
        return view('user.writer_payments', [
            'assigns' => $assigns,
            'user' => $user
        ]);
    }

    /**
     * Register user
     */

    public function register(Request $request)
    {
        if ($request->method() == 'POST') {
            if (!$request->isXmlHttpRequest()) {
                $robotChecker = new CaptchaRepository();
                $not_robot = $robotChecker->checkRobot($request);
                if (!$not_robot) {
                    return redirect("user/register")
                        ->withErrors([
                            'robot' => "Hey, $request->name. You Are a suspected Robot!"
                        ])
                        ->withInput();
                }
            }
            $emailer = new EmailRepository();
            $websiteRepo = new WebsiteRepository();
            $website_id = $websiteRepo->getWebsite()->id;
            $exists = User::where([
                ['website_id', '=', $website_id],
                ['email', 'like', $request->email]
            ])->count();
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'role' => 'required|max:255',
                'phone' => 'required|max:18',
                'email' => 'required|email|max:255',
                'password' => 'required|confirmed|min:6',
                'country' => 'required'
            ]);
            if ($exists > 0) {
                $validator->errors()->add('email', 'Email has already been taken');
            }
            if ($validator->fails()) {
                if ($request->isXmlHttpRequest()) {
                    echo $validator->errors();
                    die();
                }
                return redirect("user/register")
                    ->withErrors($validator)
                    ->withInput();
            }
            $writer_category = WriterCategory::where('deleted', '=', 0)->orderBy('cpp', 'asc')->first();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'layout' => env('LAYOUT', 'gentella'),
                'role' => $request->role,
                'website_id' => $website_id,
                'country' => $request->country,
                'password' => bcrypt($request->password)
            ]);
            $user->writer_category_id = $writer_category->id;
            $user->update();
            //            $this->notifyLoginSuccess($user,$request);
            Auth::login($user);
            $emailer->sendRegistrationEmail($user);
            if ($request->isXmlHttpRequest()) {
                echo $user->toJson();
                die();
            }
            return redirect('order')->with('notice', ['class' => 'success', 'message' => 'Registration successful']);
        }
        //$ip_api = "http://www.geoplugin.net/json.gp?ip=154.77.123.44";
        $ip_api = "http://www.geoplugin.net/json.gp?ip=" . $request->ip();
        //        $country = @json_decode(@file_get_contents(@$ip_api))->geoplugin_countryName;
        return view('auth.register', [
            'country' => null
        ]);
    }

    public function setProfile(Request $request)
    {
        $step = $request->step;
        $profile_step = (int) @$this->user->profile->step;

        if ($step > $profile_step) {
            return redirect('user/complete_profile?step=' . $profile_step)->with('notice', ['class' => 'error', 'message' => 'Please complete the previous step']);
        }
        if ($request->method() == 'POST') {
            if (!$step) {
                $fileSaver = new FileSaverRepository();
                $file = $request->file('cv');
                $exploded = explode('.', $file->getClientOriginalName());
                $extension = $exploded[count($exploded) - 1];
                $allowed = ['pdf', 'docx', 'doc', 'dox'];

                if (!in_array($extension, $allowed)) {

                    return redirect('user/complete_profile')->withErrors([
                        'cv' => 'File Not Allowed, .pdf .docx and doc are allowed'
                    ])->withInput();
                }
                $file = $fileSaver->uploadFile($file, 'yes');
                //                dd($file);
                if (isset($this->user->profile)) {
                    $id = $this->user->profile->id;
                } else {
                    $id = null;
                }
                $this->user->profile()->updateOrCreate(['id' => $id], [
                    'academic_id' => $request->academic_id,
                    'about' => $request->about,
                    'native_language' => $request->native_language,
                    'cv_file_id' => $file->id,
                    'step' => 1
                ]);
                return redirect("user/complete_profile?step=1")->with('notice', ['class' => 'success', 'message' => 'Background information saved']);
            }
            if ($step == 1) {
                $fileSaver = new FileSaverRepository();
                $file = $request->file('cert');
                $exploded = explode('.', $file->getClientOriginalName());
                $extension = $exploded[count($exploded) - 1];
                $allowed = ['pdf', 'docx', 'doc', 'dox', 'jpg', 'jpeg', 'png'];
                if (!in_array($extension, $allowed)) {
                    return redirect('user/complete_profile?step=1')->withErrors([
                        'cert' => 'File Not Allowed, .pdf .docx, jpg,jpeg,png and doc are allowed'
                    ])->withInput();
                }
                $file = $fileSaver->uploadFile($file, 'yes');
                $this->user->profile()->update([
                    'cert_title' => $request->cert_title,
                    'cert_file_id' => $file->id,
                    'step' => 2
                ]);
                return redirect("user/complete_profile?step=2")->with('notice', ['class' => 'success', 'message' => 'Background information saved']);
            }
            if ($step == 2) {
                $fileSaver = new FileSaverRepository();
                $file_1 = $request->file('file_1');
                $exploded_1 = explode('.', $file_1->getClientOriginalName());
                $extension_1 = $exploded_1[count($exploded_1) - 1];
                $file_2 = $request->file('file_2');
                $exploded_2 = explode('.', $file_2->getClientOriginalName());
                $extension_2 = $exploded_2[count($exploded_2) - 1];
                $allowed = ['pdf', 'docx', 'doc', 'dox'];
                $error1 = null;
                $error2 = null;
                if (!in_array($extension_1, $allowed)) {
                    $error1 = 'File Not Allowed, .pdf .docx and doc are allowed';
                }
                if (!in_array($extension_2, $allowed)) {
                    $error2 = 'File Not Allowed, .pdf .docx and doc are allowed';
                }
                if ($error1 != null || $error1 != null) {
                    //                    dd($errors);
                    return redirect('user/complete_profile?step=2')->withErrors([
                        'file_1' => $error1,
                        'file_2' => $error2
                    ])->withInput();
                }
                $file_1 = $fileSaver->uploadFile($file_1, 'yes');
                $file_2 = $fileSaver->uploadFile($file_2, 'yes');
                $title_1 = $request->title_1;
                $title_2 = $request->title_2;
                $sample_essays = [];
                $sample_essays[] = [
                    'file_id' => $file_1->id,
                    'title' => $title_1
                ];
                $sample_essays[] = [
                    'file_id' => $file_2->id,
                    'title' => $title_2
                ];
                $sample_string = json_encode($sample_essays);
                $this->user->profile()->update([
                    'sample_essays' => $sample_string,
                    'step' => 3
                ]);
                return redirect("user/complete_profile?step=3")->with('notice', ['class' => 'success', 'message' => 'Background information saved']);
            }
            if ($step == 3) {
                $subject_ids = $request->subject_ids;
                $style_ids = $request->style_ids;
                $subject_error = null;
                $style_error = null;
                if (count($subject_ids) < 1) {
                    $subject_error = "Select at least one subject";
                }
                if (count($style_ids) < 1) {
                    $style_error = "Select at least one style";
                }
                if ($style_error || $subject_error) {
                    return redirect("user/complete_profile?step=3")->withErrors([
                        'subject_ids' => $subject_error,
                        'style_ids' => $style_error
                    ])->withInput();
                }
                $this->user->profile()->update([
                    'subject_ids' => json_encode($subject_ids),
                    'style_ids' => json_encode($style_ids),
                    'step' => 4
                ]);
                return redirect("user/complete_profile?step=4")->with('notice', ['class' => 'success', 'message' => 'Academic Qualifications saved']);
            }
            if ($step == 4) {
                $payment_terms = $request->payment_terms;
                $other_company = $request->other_company;
                $this->user->profile()->update([
                    'other_company' => $other_company,
                    'payment_terms' => $payment_terms,
                    'step' => 5
                ]);
                $emailer = new EmailRepository();
                $emailer->sendGeneralEmail('profile_completion_email', 'Profile Completed', $this->user);
                return redirect('writer')->with('notice', ['class' => 'success', 'message' => 'Profile Details submitted']);
            }
        }
        $user = $this->user;
        $styles = Style::where('deleted', '=', 0)->get();
        $subjects = Subject::where('deleted', '=', 0)->get();
        $academics = Academic::where('deleted', '=', 0)->get();
        if (!$user->profile) {
            $profile = $user->profile()->create([]);
        } else {
            $profile = $user->profile;
        }
        return view('user.complete_profile', [
            'user' => $user,
            'styles' => $styles,
            'subjects' => $subjects,
            'academics' => $academics,
            'step' => $step,
            'profile' => $profile
        ]);
    }

    public function applicationInformation(User $user)
    {
        $profile = $user->profile;
        $styles = Style::whereIn('id', @json_decode($profile->style_ids))->get();
        $subjects = Subject::whereIn('id', @json_decode($profile->subject_ids))->get();
        return view('user.application_info', [
            'user' => $user,
            'profile' => $profile,
            'styles' => $styles,
            'subjects' => $subjects
        ]);
    }

    public function writerApplications(Request $request)
    {
        $key = $request->search;
        if ($request->search) {
            if (filter_var($key, FILTER_VALIDATE_EMAIL)) {
                //email
                $applications = User::where([
                    ['role', 'LIKE', 'writer'],
                    ['status', '=', 0],
                    ['email', 'like', "%$key%"]
                ])->orderBy('id', 'desc')->paginate(10);
            } else {
                if (is_numeric($key)) {
                    //id
                    $applications = User::where([
                        ['role', 'LIKE', 'writer'],
                        ['status', '=', 0],
                        ['id', '=', $key]
                    ])->orderBy('id', 'desc')->paginate(10);
                } else {
                    //name
                    $applications = User::where([
                        ['role', 'LIKE', 'writer'],
                        ['status', '=', 0],
                        ['name', 'LIKE', "%$key%"]
                    ])->orderBy('id', 'desc')->paginate(10);
                }
            }
        } else {
            $applications = User::where([
                ['role', 'LIKE', 'writer'],
                ['status', '=', 0]
            ])->orderBy('id', 'desc')->paginate(10);
        }


        return view('user.applications', [
            'users' => $applications
        ]);
    }
    public function activateWriter(User $user)
    {
        $user->status = 1;
        $user->suspended = 0;
        $user->update();
        $emailer = new EmailRepository();
        $emailer->sendGeneralEmail('writer_approval_email', 'Application Approval', $user);
        return redirect("user/view/writer/$user->id")->with('notice', ['class' => 'success', 'message' => 'Account Activated successfully']);
    }

    public function suspend(User $user, Request $request)
    {
        if ($request->method() == 'POST') {
            $user->suspensions()->create([
                'reason' => $request->reason
            ]);
            $user->suspended = 1;
            $user->update();
            $this->emailer->message = $request->reason;
            $this->emailer->sendGeneralEmail('suspension_email', 'Account Suspended', $user);
            return redirect("user/view/$user->role/$user->id");
        }
        return view('user.suspend', [
            'user' => $user
        ]);
    }

    public function suspended()
    {
        $users = User::where('suspended', '=', 1)->orderBy('id', 'desc')->paginate(10);
        return view('user.suspended', [
            'users' => $users
        ]);
    }

    public function payout(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::findOrFail($user_id);
        $amount = $request->amount;
        $payment_for = $request->payment_for;

        if ($amount < 1) {
            return redirect("user/$user->id/payments")->with('notice', ['class' => 'error', 'message' => 'Amount to be payed is too low']);
        }
        $paypal = new PaypalRepository();
        $paypal->request = $request;
        if ($request->via == 'manual') {
            $user->payments()->create([
                'payer_id' => Auth::User()->id,
                'transaction_reference' => $request->reference,
                'amount' => $request->amount,
                'state' => 'SUCCESS',
                'method' => 'manual',
                'payment_for' => $payment_for,
            ]);
            return redirect("user/$user->id/payments")->with('notice', ['class' => 'success', 'message' => 'Writer successfully paid']);
        } else {
            $status = $paypal->payWriter($user, $amount, $payment_for, "Writer Payment");
            if ($status == 'SUCCESS') {
                return redirect("user/$user->id/payments")->with('notice', ['class' => 'success', 'message' => 'Writer successfully paid']);
            } else {
                return redirect("user/$user->id/payments")->with('notice', ['class' => 'warning', 'message' => 'Payment was not successful. Status:' . $status]);
            }
        }
    }

    public function updateCategory($role, User $user, Request $request)
    {
        if (isset($request->action_element_id)) {
            if ($user->is_author) {
                $user->is_author = 0;
            } else {
                $user->is_author = 1;
            }
            $user->update();
            return ['reload' => true];
        }
        $user->writer_category_id = $request->writer_category_id;
        $user->update();
        return redirect("user/view/$user->role/$user->id")->with('notice', ['class' => 'info', 'message' => 'Writer category changed']);
    }

    public function deleteUser(User $user)
    {
        $user->assigns()->delete();
        $user->profile()->delete();
        $user->bids()->delete();
        $role = $user->role;
        $user->delete();
        return redirect("user/view/$role")->with('notice', ['class' => 'info', 'message' => 'User deleted']);
    }

    public function addPhone(Request $request)
    {
        $this->user->phones()->create($request->all());
        return ['reload' => true];
    }
    public function deletePhone(Phone $phone)
    {
        $phone->delete();
        return ['reload' => true];
    }

    public function addPoints($role, User $user, Request $request)
    {
        $points = $request->points;
        $reason = $request->reason;
        $user->addonPoints()->create([
            'points' => $points,
            'reason' => $reason
        ]);
        return ['reload' => true];
    }
    public function topUpAccount($role, User $user, Request $request)
    {
        $amount = $request->amount;
        $reference = $request->reference;
        $via = $request->via;
        $user->accountTopUps()->create([
            'amount' => $amount,
            'via' => $via,
            'usd_rate' => 1,
            'reference' => $reference,
            'redeemed_points' => 0
        ]);
        return ['reload' => true];
    }

    public function adminUpdate(User $user, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'country' => 'required'
        ]);
        if ($user->email != $request->email) {
            $exists = User::where([
                ['email', 'like', $request->email],
                ['website_id', '=', $user->website_id]
            ])->first();
            if ($exists)
                return response(['email' => 'Email has already been taken'], 422);
        }

        $user->email = $request->email;
        $user->name = $request->name;
        $user->country = $request->country;
        $user->phone = $request->phone;
        $user->update();
        return ['reload' => true];
    }
}
