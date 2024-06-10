<?php

namespace App\Http\Controllers;

use App\Academic;
use App\Currency;
use App\File;
use App\Order;
use App\Repositories\EmailRepository;
use App\Repositories\MenuRepository;
use App\Repositories\PdfToImage;
use App\Repositories\WebsiteRepository;
use App\Repositories\WordRepository;
use App\User;
use Mockery\CountValidator\Exception;
use PDF;
use Illuminate\Http\Request;
use App\Document;
use App\Rate;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Urgency;
use App\Subject;
use App\Repositories\OrderRepository;
use App\BidMapper;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Validator;
use URL;
use Storage;
use Response;

class ExternalController extends Controller
{
    //
    public function __construct(Request $request)
    {
        new MenuRepository($request);
    }


    /**
     * Guest placing a new order
     */
    public function checkEmail(Request $request){
        $web = new WebsiteRepository();
        $website_id = $web->getWebsiteId();
        $exists = User::where([
            ['website_id','=',$website_id],
            ['email','=',$request->email]
        ])->get();
        if($exists){
            return ['status'=>false,'error'=>'Email has already been taken'];
        }else{
            return ['status'=>true];
        }
    }
    public function guestOrder(Request $request){
        if($request->method()=='POST'){
            $order = Order::create([
                'topic'=>$request->topic,
                'subject'=>$request->subject,
                'document'=>$request->document,
                'spacing'=>$request->spacing,
                'pages'=>$request->pages,
                'sources'=>$request->sources,
                'style'=>$request->style,
                'language'=>$request->language,
                'instructions'=>$request->instructions,
                'urgency'=>$request->urgency,
                'academic_level'=>$request->academic_level
            ]);
            $orderRepo = new OrderRepository();
            $order->status = 10;
            $orderRepo->calculateCost($order);          
            return redirect("guest/preview/$order->id");
        }
        $urgencies = Urgency::get();
        $docs = Subject::where('doc_type','=','docs')->get();
        $subjects = Subject::where('doc_type','=','subject')->get();
        return view('guest_order',[
            'urgencies'=>$urgencies,
            'docs'=>$docs,
            'subjects'=>$subjects
        ]);
    }

    /**
     * Guest preview order
     */

    public function preview(Order $order,Request $request){

        if($request->method()=='POST'){
            $emailer = new EmailRepository();
            $websiteRepo = new WebsiteRepository();
            $website_id = $websiteRepo->getWebsiteId();
            $exists = User::where([
                ['website_id','=',$website_id],
                ['email','=',$request->email]
            ])->get();
            $ip_api = "http://www.geoplugin.net/json.gp?ip=154.77.123.44";
//        $ip_api = "http://www.geoplugin.net/json.gp?ip=".$request->ip();
            $country = @json_decode(file_get_contents($ip_api))->geoplugin_countryName;
            if(!$country){
                $country = "Kenya";
            }
            $request->country = $country;
           $validator= Validator::make($request->all(),[
                'name' => 'required|max:255',
                'phone' => 'required|max:18',
                'email' => 'required|email|max:255',
                'password' => 'required|confirmed|min:6',
            ]);
            if(count($exists)>0){
                return redirect("stud/preview")
                    ->withErrors([
                        'email'=>'Email has already been used'
                    ])
                    ->withInput();
            }
            if ($validator->fails()) {
                return redirect("stud/preview")
                    ->withErrors($validator)
                    ->withInput();
            }
            $user = User::create([
               'name'=>$request->name,
                'email'=>$request->email,
                'phone'=>$request->phone,
                'layout'=>env('LAYOUT','gentella'),
                'role'=>env('ROLE','client'),
                'website_id'=>$website_id,
                'country'=>$country,
                'password'=>bcrypt($request->password)
            ]);
            $order->user_id=$user->id;
            $order->update();
            Auth::login($user);
            $emailer->sendRegistrationEmail($user);
            return redirect("stud/preview");
        }
       $ip_api = "http://www.geoplugin.net/json.gp?ip=".$request->ip();      
        $country = json_decode(file_get_contents($ip_api))->geoplugin_countryName;
        return view('preview',[
            'order'=>$order,
            'country'=>$country
        ]);
    }

    /**
     * @param File $file
     * Preview file - Orders not yet approved or partially paid use these option
     */
    public function previewFile(File $file){
        $url = url("order/download/$file->id/previewer");
        $order = $file->order;
        if(!$order){
            $assign = $file->assign;
            $order = $assign->order;
        }
        return view('client.preview_file',[
                'file'=>$file,
                'url'=>$url,
                'order'=>$order
            ]);
        $path = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().$file->path;
            $filetype = $file->file_type;
            $filename = $file->filename;
            $headers = array(
                'Content-Type'=>$filetype
            );
            return Response::download($path,$filename,$headers);
//         new \DOMPDF();

// //        \PhpOffice\PhpWord\Settings::setPdfRenderer(Dom);
// //        \PhpOffice\PhpWord\Settings::setPdfRendererName('TCPDF');
        $word_repo = new WordRepository();
//         $phpword = new PhpWord();
//          $document = $phpword->loadTemplate(storage_path('app').'/'.$file->path);
//        // exit;
//          $document->saveAs('temp.docx');
//         $pword = IOFactory::load("temp.docx");
// //        exit;
//         //Save it
//         $xmlWriter = IOFactory::createWriter($pword , 'HTML');

//         $public_path = getcwd();
//         if(!is_dir($public_path.'/pdfs/')){
//             mkdir($public_path.'/pdfs/');
//         }
//         $storage_path = $public_path.'/pdfs/'.$file->id.'.html';
// //        $xmlWriter
//         $xmlWriter->save($storage_path);
// //        $pdf->st($storage_path);
// //        exit;$pdf = PDF::loadHTML('<div style="font-size:large;">'.$content.'</div>',array("Attachment" => false));
         // return $pdf->stream();
        $content = $word_repo->readWord($file->path);


        if($content==false){
            return redirect()->back()->with('notice',['class'=>'error','message'=>'Error occurred reading file. Please contact admin! ']);
        }
        $pdf = PDF::loadHTML('<div style="font-size:large;">'.$content.'</div>',array("Attachment" => false));
         return $pdf->stream();
        $pdf = PDF::load($storage_path,array("Attachment" => false));
         return $pdf->stream();
         exit;

        $public_path = getcwd();       
        if(!is_dir($public_path.'/pdfs/')){
            mkdir($public_path.'/pdfs/');
        }
        $storage_path = $public_path.'/pdfs/'.$file->id.'.pdf';
      $pdf->save($storage_path);

        try{
            $pdfimage = new PdfToImage($storage_path);
            $pages = $pdfimage->getNumberOfPages();
        }catch (\Exception $e){            
           return $this->requestHelp($file->id);
            exit;
        }

        $image_path = $public_path.'/pdf_images/'.$file->id;
        if(!is_dir($image_path)){
            mkdir($image_path);
        }
        $image_string = "";
        for($i=1;$i<=$pages;$i++){
            $img = '/image_'.$i.'.png';
            $image_name = $image_path.$img;
            $pdfimage->setPage($i);
            $pdfimage->saveImage($image_name);
            if(getimagesize(URL::to('pdf_images/'.$file->id.$img))){
                $image_string.='<img style="width:100%;margin-right:10px;" src="'.URL::to('pdf_images/'.$file->id.$img).'">';
            }else{
                $image_string.='<img style="width:100%;margin-right:10px;" src="'.URL::to('pdf_images/public/'.$file->id.$img).'">';
            }
        }
        $pdf = PDF::loadHTML($image_string,array("Attachment" => false));
        return $pdf->stream();
    }

    /**
     * Search for promotion
     */

    public function search(Request $request){
        $promotions = Promotion::where('code','=',$request->code)->get();
        $order = Order::find($request->order_id);
        if($order->status==10){
            if(count($promotions)>0){
                $promotion = $promotions[0];
                $percentage = $promotion->percent;
            }else{
                $percentage = 0;
            }
            $order->discounted = $percentage;
            $order->update();
            echo $percentage;
        }
    }

    public function getRates(){
        $documents= Document::where('deleted','=','0')->get();
        $subjects = Subject::where('deleted','=','0')->get();
        $rates = Rate::where('deleted','=',0)->orderBy('hours','desc')->get();
        $academics = Academic::where('deleted','=',0)->get();
        $currencies = Currency::where('deleted','=',0)->get();
        $web_repo = new WebsiteRepository();
        $website = $web_repo->getWebsite();
        $data = ['documents'=>$documents,'subjects'=>$subjects,'rates'=>$rates,'academics'=>$academics,'currencies'=>$currencies,'website'=>$website];
        echo json_encode($data);
    }

        /**
     * User login using get from wordpress or post from system
     */
    public function login(Request $request){
        $email = $request->email;
        $password = $request->password;
        $webRepo = new WebsiteRepository();
        $website_id = $webRepo->getWebsiteId();
            if (Auth::attempt(['email' => $email, 'password' => $password,'website_id'=>$website_id], false)) {
                $response = ['status' => true,'url'=>URL::to(env('HOME','stud'))];
            } else {
                $response = ['status' => false, 'error' => 'Invalid email or password'];
            }
        if($request->isXmlHttpRequest()){
            echo json_encode($response);
        }else{
            if($response['status']){
                return redirect(env('HOME'))->with('notice',['class'=>'info','message'=>'Welcome back to your order management panel']);
            }else{
                return redirect('login')->withErrors([
                    'email'=>'These credentials do not match our records.'
                ]);
            }
        }

    }

    public function helpImage(Request $request){
        $domain = "http://".$request->domain.'/'.$request->folder.'/'.$request->file_id.'.pdf';
        $dir = '/pdfs/'.str_replace(".", "_", $request->domain).'/';
        $path = $dir.$request->file_id.'.pdf';
       $file = Storage::put($path, file_get_contents($domain));
       $full_file_path = storage_path("app").$path;
        $pdfimage = new PdfToImage($full_file_path);
        $pages = $pdfimage->getNumberOfPages();
        $image_path = '/home1/iankibet/public_html/orders/pdf_images/'.$request->file_id;
        if(!is_dir($image_path)){
            mkdir($image_path,0755,true);
        }
        $image_string = "";
        for($i=1;$i<=$pages;$i++){
            $img = '/image_'.$i.'.png';
            $image_name = $image_path.$img;
            $pdfimage->setPage($i);
            $pdfimage->saveImage($image_name);
            if(@getimagesize(URL::to('pdf_images/'.$request->file_id.$img))){
                $image_string.='<img style="width:100%;margin-right:10px;" src="'.URL::to('pdf_images/'.$request->file_id.$img).'">';
            }else{
                $image_string.='<img style="width:100%;margin-right:10px;" src="'.URL::to('pdf_images/public/'.$request->file_id.$img).'">';
            }
        }

        return ['status'=>true,'image_string'=>$image_string];

    }

    public function requestHelp($file_id){
        if(@filesize(URL::to('pdfs/'.$file_id.".pdf"))){
            $folder='orders/pdfs';
        }else{
            $folder="orders/pdfs";
        }
        $data = ['domain'=>$_SERVER['HTTP_HOST'],'folder'=>$folder,'file_id'=>$file_id];
        $url = "http://academicwritinglab.com/orders/api/help-image?".http_build_query($data);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, null);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl,CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        $content = curl_exec($curl);
        if($return = @json_decode($content)){
            $status = $return->status;
            $image_string  = $return->image_string;
            $pdf = PDF::loadHTML($image_string,array("Attachment" => false));            
            return $pdf->stream();
        }
    }
}
