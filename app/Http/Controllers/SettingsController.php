<?php

namespace App\Http\Controllers;

use App\Document;
use App\Repositories\MenuRepository;
use App\Style;
use App\Subject;
use App\Urgency;
use Illuminate\Http\Request;
use App\Language;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use PhpParser\Comment\Doc;

class SettingsController extends Controller
{
    //
    public function __construct(Request $request)
    {
        new MenuRepository($request);
    }

    public function index(){
        $urgencies = Urgency::paginate(10);
        return view('settings.index',[
            'urgencies'=>$urgencies
        ]);
    }

    public function rates(Request $request){
        $method = $request->method();
        if($method=='POST'){
            $urgent = new Urgency();
            if($request->id){
                $urgent = Urgency::find($request->id);
            }
            $saver = new Urgency();
            if($saver->exchangeSave($request,$urgent)){
                $request->session()->flash('notice',['class'=>'success','message'=>'Rate has been saved!']);
            }
        }
        $urgencies = Urgency::paginate(10);
        return view('settings.rates',[
            'urgencies'=>$urgencies
        ]);
    }

    public function subjects(Request $request){
        if($request->search){
            $key = $request->search;
            if(is_numeric($key)){
                $subjects = Subject::where([
                    ['deleted','=',0],
                    ['id','like',"$key"]
                ])
                    ->orderBy('id','desc')->paginate(10);
            }else{
                $subjects = Subject::where([
                 ['deleted','=',0],
                 ['label','like',"%$key%"]
                ])
                    ->orderBy('id','desc')->paginate(10);
            }
        }else{
            $subjects = Subject::where('deleted','=',0)->orderBy('id','desc')->paginate(10);
        }

        return view('settings.subjects.index',[
            'subjects'=>$subjects
        ]);
    }

    public function documents(Request $request){
        if($request->search){
            $key = $request->search;
            if(is_numeric($key)){
                $documents = Document::where([
                    ['deleted','=',0],
                    ['id','like',"$key"]
                ])
                    ->orderBy('id','desc')->paginate(10);
            }else{
                $documents = Document::where([
                    ['deleted','=',0],
                    ['label','like',"%$key%"]
                ])
                    ->orderBy('id','desc')->paginate(10);
            }
        }else{
            $documents = Document::where('deleted','=',0)->orderBy('id','desc')->paginate(10);
        }

        return view('settings.documents.index',[
            'documents'=>$documents
        ]);
    }

    public function addDocument(Request $request){
        $document = Document::findOrNew($request->id);
        if($request->method()=='POST'){
            $document->label = $request->label;
            $document->inc_type = $request->inc_type;
            $document->amount = $request->amount;
                $document->save();
            return redirect('settings/documents')->with('notice',['class'=>'success','message'=>'document Saved']);
        }
        return view('settings.documents.new',[
            'document'=>$document
        ]);
    }
    
    public function addSubject(Request $request){
        $subject = Subject::findOrNew($request->id);
        if($request->method()=='POST'){
            $subject->label = $request->label;
            $subject->inc_type = $request->inc_type;
            $subject->amount = $request->amount;
                $subject->save();
            return redirect('settings/subjects')->with('notice',['class'=>'success','message'=>'Subject Saved']);
        }
        return view('settings.subjects.new',[
            'subject'=>$subject
        ]);
    }

    public function deleteSubject(Subject $subject){
        $subject->deleted = 1;
        $subject->update();
        return redirect('settings/subjects')->with('notice',['class'=>'success','message'=>'Subject Deleted']);

    }

    public function deleteDocument(Document $document){
        $document->deleted = 1;
        $document->update();
        return redirect('settings/documents')->with('notice',['class'=>'success','message'=>'Subject Deleted']);

    }

    public function deleteUrgency(Urgency $urgency){
        $urgency->delete();
        return redirect("settings/rates")->with('notice',['class'=>'success','message'=>'Rate deleted']);
    }
    public function addUrgency(Request $request, $id){
        if($id){
            $urgency = Urgency::findOrFail($id);
        }else{
            $urgency = new Urgency();
        }
        return view('settings.rate_form',[
           'urgency'=>$urgency
        ]);
    }

    public function addCategory($type,$id,Request $request){
        if($id){
            $subject = Subject::findOrFail($id);
        }else{
            $subject = new Subject();
        }
        if($request->method()=='POST'){
            if(!$id){
                $this->validate($request, [
                    'slug' => 'unique:subjects',
                ]);
            }

            $request->doc_type = $type;
            $saver = new Subject();
           if($saver->exchangeSave($request,$subject)){
               return redirect("settings/category/$type")->with('notice',['class'=>'success','message'=>'Saved successfully']);
           };
        }
        return view('settings.category_form',[
           'type'=>$type,
            'subject'=>$subject
        ]);
    }

    public function styles(){
        $styles = Style::where('deleted','=',0)->paginate(10);
        return view('settings.styles.index',[
            'styles'=>$styles
        ]);
    }

    public function addStyle(Request $request){
        $style = Style::findOrNew($request->id);
        if($request->method()=='POST'){
            $style->label = $request->label;
            $style->inc_type = $request->inc_type;
            $style->amount = $request->amount;
            $style->save();
            return redirect("settings/styles")->with('notice',['class'=>'success','message'=>'Saved successfully']);

        }
        return view('settings.styles.new',[
            'style'=>$style
        ]);
    }

    public function deleteStyle(Style $style){
        $style->delete();
        return redirect("settings/styles")->with('notice',['class'=>'success','message'=>'Style deleted']);

    }

    public function languages(){
        $languages = Language::where('deleted','=',0)->paginate(10);
        return view('settings.languages.index',[
            'languages'=>$languages
        ]);
    }

    public function addLanguage(Request $request){
        $language = Language::findOrNew($request->id);
        if($request->method()=='POST'){
            $language->label = $request->label;
            $language->inc_type = $request->inc_type;
            $language->amount = $request->amount;
            $language->save();
            return redirect("settings/languages")->with('notice',['class'=>'success','message'=>'Saved successfully']);

        }
        return view('settings.languages.new',[
            'language'=>$language
        ]);
    }

    public function deleteLanguage(Language $language){
        $language->delete();
        return redirect("settings/languages")->with('notice',['class'=>'success','message'=>'Language deleted']);

    }    
}
