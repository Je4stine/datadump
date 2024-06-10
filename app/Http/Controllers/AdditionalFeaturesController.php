<?php

namespace App\Http\Controllers;

use App\AdditionalFeature;
use App\Repositories\MenuRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
class AdditionalFeaturesController extends Controller
{
    //
    protected $user;
    protected $folder = "settings.additional_features.";

    public function __construct(MenuRepository $menuRepository)
    {
        $menuRepository->check();
        $this->user = Auth::user();
    }

    public function index()
    {
        $features = AdditionalFeature::all();
        return view($this->folder.'index',[
            'features'=>$features
        ]);
    }

    public function store(Request $request){
        $add = AdditionalFeature::find($request->id);
        $data = $request->all();
        unset($data['_token']);
        if($add){
            $add->update($data);
        }else{
            $add = AdditionalFeature::create($data);
        }
        $features = AdditionalFeature::all();
        if($request->isXmlHttpRequest()){
            return ['reload'=>true];
        }
        return view($this->folder.'index',[
            'features'=>$features
        ]);
    }

    public function delete(AdditionalFeature $additionalFeature){
        $additionalFeature->delete();
        return redirect("additional-features");
    }
}
