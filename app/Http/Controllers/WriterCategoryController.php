<?php

namespace App\Http\Controllers;

use App\Repositories\MenuRepository;
use App\WriterCategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class WriterCategoryController extends Controller
{
    //
    protected $folder = 'writer_category.';
    public function __construct(MenuRepository $menuRepository)
    {
        $menuRepository->check();
    }

    public function index(){
        $writer_categories = WriterCategory::where('deleted','=',0)->paginate(10);
        return view($this->folder.'index',[
            'writer_categories'=>$writer_categories
        ]);
    }

    public function add(Request $request){
        $writer_category = WriterCategory::findOrNew($request->id);
        if($request->method()=='POST'){
            $writer_category->name = $request->name;
            $writer_category->cpp  = $request->cpp;
            $writer_category->amount = $request->amount;
            $writer_category->inc_type = $request->inc_type;
            $writer_category->deadline = $request->dline;
            $writer_category->fine_percent = $request->fine_percent;
            $writer_category->description = $request->description;
            $writer_category->save();
            return redirect("writer_categories")->with('notice',['class'=>'success','message'=>'Category Added']);
        }
       return view($this->folder.'new',[
           'writer_category'=>$writer_category
       ]) ;
    }

    public function delete($id){
        $writer_category = WriterCategory::findOrFail($id);
        $writer_category->deleted = 1;
        $writer_category->update();
        return redirect("writer_categories")->with('notice',['class'=>'success','message'=>'Category Deleted']);

    }

}
