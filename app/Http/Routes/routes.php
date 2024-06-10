<?php
// allow origin
header('Access-Control-Allow-Origin: *');
// add any additional headers you need to support here
header('Access-Control-Allow-Headers: Origin, Content-Type');
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


Route::get('payza/ipn','HomeController@payzaIpn');
Route::group(['middleware' => ['web']], function () {
     Route::get('order/download/{file}/previewer',function(\App\File $file){
        return response()->download(storage_path('app/'.$file->path));
          // dd();
    });
    // Route::get('order/download/{file}/preview',function($file){
    //     return view('file_previewer',[
    //             'file_id'=>$file
    //         ])
    //       // dd();
    // });
    //
    
    Route::post('users/register','HomeController@register');

    Route::post('users/login','HomeController@login');
    Route::group(['prefix' => 'api'], function () {
        Route::get('check-email','ExternalController@checkEmail');
        Route::get('help-image','ExternalController@helpImage');
        Route::get('/login','UserController@login');
        Route::get('/login/self','ExternalController@self');
        Route::get('/rates','ExternalController@getRates');
        Route::get('is_loggedin',function(){
           echo json_encode(Auth::user());
        });
    });
    Route::group(['prefix' => 'guest'], function () {
        Route::any('/order','ExternalController@guestOrder');
        Route::any('/preview/{order}','ExternalController@preview');
    });
    Route::get('/', function () {
    return redirect('order');
});


    Route::Auth();

    //Pre-injected routed to order controller


    Route::post('admin/search','OrderController@search');    
    Route::group(['prefix' => 'promotions'], function () {
        Route::get('/','PromotionController@index');
        Route::any('/add','PromotionController@add');
        Route::get('/changestatus/{promotion}/{status}','PromotionController@changeStatus');
        Route::get('/delete/{promotion}','PromotionController@delete');
        Route::get('/search','PromotionController@search');
    });

    Route::group(['prefix' => 'config'], function () {
        Route::get('/','ConfigurationController@index');
    });


    Route::group(['prefix' => 'writer_categories'], function () {
        Route::get('/','WriterCategoryController@index');
        Route::any('/add','WriterCategoryController@add');
        Route::get('/delete/{writer_category}','WriterCategoryController@delete');
    });
    Route::group(['prefix' => 'forgot'], function () {
        Route::get('password','ResetController@index');
        Route::post('password','ResetController@index');
        Route::get('reset','ResetController@reset');
        Route::post('reset','ResetController@reset');
    });

    Route::group(['prefix' => 'admin_groups'], function () {
        Route::get('/','AdminGroupController@index');
        Route::get('/add','AdminGroupController@add');
        Route::post('/add','AdminGroupController@save');
        Route::get('/view/{id}','AdminGroupController@view');
        Route::post('/view/{id}','AdminGroupController@addUser');
    });

    Route::group(['prefix' => 'emails'], function () {
               Route::get('/send','EmailController@sendEmails');
               Route::put('/send','EmailController@editEmail');
               Route::post('/send','EmailController@mailUsers');
    });

    Route::group(['prefix' => 'currency'], function () {
        Route::get('/','CurrencyController@index');
        Route::get('/add','CurrencyController@add');
        Route::post('/add','CurrencyController@save');
        Route::get('/delete/{currency}','CurrencyController@delete');
    });
    Route::group(['prefix' => 'download'], function () {
        Route::get('/path',function(\Illuminate\Http\Request $request){
          $path = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().$request->path;
            $filetype = $request->file_type;
            $filename = $request->filename;
            $headers = array(
                'Content-Type'=>$filetype
            );
            Return Response::download($path,$filename,$headers);
        });
    });

    Route::group(['prefix' => 'fines'], function () {
        Route::get('/remove/{fine}','FinesController@remove');
        Route::post('/update','FinesController@update');
    });
    Route::group(['prefix' => 'updates'], function () {
        Route::get('/','UpdatesController@index');
        Route::post('/','UpdatesController@updateNow');
    });
    Route::group(['prefix' => 'additional-features'], function () {
        Route::get('/','AdditionalFeaturesController@index');
        Route::get('delete/{additionalFeature}','AdditionalFeaturesController@delete');
        Route::post('/','AdditionalFeaturesController@store');
    });
});
