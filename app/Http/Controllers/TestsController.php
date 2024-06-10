<?php

namespace App\Http\Controllers;

use App\File;
use App\RandomTest;
use App\Repositories\EmailRepository;
use App\Repositories\FileSaverRepository;
use App\Repositories\MenuRepository;
use App\WriterTest;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class TestsController extends Controller
{
    //
    protected $folder = "tests.";
    public function __construct(Request $request)
    {
        new MenuRepository($request);
    }

    public function settings(){
        $random_tests = RandomTest::paginate(10);
        return view('settings.tests.index',[
            'random_tests'=>$random_tests
        ]);
    }

    public function store(Request $request){
        $test = RandomTest::findOrNew($request->id);
        $test->topic = $request->topic;
        $test->instructions = $request->instructions;
        $test->user_id  = $request->user()->id;
        $test->duration = $request->duration;
        $test->save();
        return ['reload'=>true];
    }

    public function changeStatus(RandomTest $test){
        if($test->active == 1){
            $test->active = 0;
        }else{
            $test->active = 1;
        }
        $test->update();
        return ['reload'=>true];
    }

    public function writerTest(){
        return view('writer.test');
    }

    public function start(WriterTest $test){
        $paper = $test->randomTest;
        $now = Carbon::now();
        $now2 = Carbon::now();
        $deadline = $now2->addMinutes($paper->duration);
        $test->started_at = $now->toDateTimeString();
        $test->complete_time = $deadline->toDateTimeString();
        $test->save();
        return ['reload'=>true];
    }

    public function submitTest(Request $request){
        $test = WriterTest::findOrFail($request->id);
        $fileUploader = new FileSaverRepository();
        $file = $fileUploader->uploadFile($request->file('essay_file'),true);
        $now = Carbon::now();
        $expected_complettion = Carbon::createFromTimestamp(strtotime($test->started_at));
        $is_late = 1;
        if($now>$expected_complettion){
            $is_late = 0;
        }
        $test->is_late = $is_late;
        $test->file_id = $file->id;
        $test->update();
        $user = $request->user();
        $user->test_done = 1;
        $user->update();
        $emailer = new EmailRepository();
        $emailer->sendAdminNote("Hello Admin<br/>A writer $user->name($user->email) Has just completed an essay test.<br/>Please login to your account and check on writer applications");
        return redirect("writer");
    }
}
