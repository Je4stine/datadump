<?php

namespace App\Http\Controllers;

use App\Department;
use App\Message;
use App\Repositories\EmailRepository;
use App\Repositories\MenuRepository;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\OrderMessage;

class DepartmentsController extends Controller
{
    //
    protected $folder = "departments.";
    protected $user;
    protected $emailer;
    public function __construct(MenuRepository $menuRepository)
    {
        $menuRepository->check();
        $this->user = Auth::user();
        $this->emailer = new EmailRepository();
    }

    public function index(){
        $departments = Department::get();
        return view($this->folder.'index',[
            'departments'=>$departments
        ]);
    }

    public function saveDepartment(Request $request){
        $department = Department::findOrNew($request->id);
        $department->name = $request->name;
        $department->description = $request->description;
        $department->save();
        return redirect("departments")->with('notice',['class'=>'info','message'=>'Department Saved']);
    }

    public function messages(){
        if($this->user->role != 'client'){
            $q = Message::leftJoin('departments','departments.id','=','messages.department_id')
                ->join('users','messages.client_id','=','users.id')
                ->select('departments.name','messages.*','users.email');
            if($this->user->role=='writer'){
                $q->where('messages.sender','=',0);
                $q->where('messages.client_id','like',$this->user->id);
            }else{
                $q->where('messages.sender','=',1);
            }
//        $q->groupBy('departments.id');

            $messages = $q->orderBy('messages.id','desc')->paginate(10);
        }else{
            $messages = OrderMessage::where([
                ['client_id','=',$this->user->id],
                ['sender','=',1]
            ])->orderBy('id','desc')->paginate(10);
        }

        $departments = Department::all();
        return view($this->folder.'messages',[
            'messages'=>$messages,
            'departments'=>$departments
        ]);
    }

    public function newMessage(Request $request){
        if(isset($request->writer_id[0])){
            $department = Department::findOrFail($request->department_id);
            $department->messages()->create([
                'user_id'=>$this->user->id,
                'sender'=>$request->sender,
                'message'=>$request->message,
                'client_id'=>$request->writer_id[0]
            ]);
            if($request->sender == 0){
                $this->emailer->sendRoomEmail(User::find($request->writer_id[0]),null,null,$request->message,'writer_new_message');
            }else{
                $mail = 'Hello Admin,<br/> You have a new message from '.User::find($request->writer_id[0])->name.'<br/>
            <strong><i>'.$request->message.'</i></strong>
             <br/>please check and reply accordingly';
                $this->emailer->sendAdminNote($mail);
            }
            return redirect("departments/messages");
        }
    }

    public function conversation(Department $department,User $user, Request $request){
        if($this->user->role=='admin'){
            $messages = $department->messages()->where('client_id','=',$user->id)->get();
            $department->messages()->where([
                ['client_id','=',$user->id],
                ['sender','=',1]
            ])->update([
                'seen'=>1
            ]);
        }else{

            if($user->id != $this->user->id){
                echo "Unauthorized!";
                exit;
            }
            $messages = $department->messages()->where('client_id','=',$user->id)->get();
            $department->messages()->where([
                ['client_id','=',$user->id],
                ['sender','=',0]
            ])->update([
                'seen'=>1
            ]);
        }


        if($request->isXmlHttpRequest()){

            return $messages;
        }else{

        }
//        dd($messages);
        return view($this->folder.'conversation',[
            'messages'=>$messages,
            'user'=>$user,
            'department'=>$department
        ]);
    }

    public function roomMessage(Department $department,\App\User $user, Request $request)
    {
        $department->messages()->create([
            'user_id'=>$this->user->id,
            'sender'=>$request->sender,
            'message'=>$request->message,
            'client_id'=>$request->client_id
        ]);
//        $user = User::find($request->client_id);
        if($request->sender == 0){
            $this->emailer->sendRoomEmail($user,null,null,$request->message,'New Message');
        }else{
            $mail = 'Hello Admin,<br/> You have a new message from '.$user->name.'<br/>
            <strong><i>'.$request->message.'</i></strong>
             <br/>please check and reply accordingly';
            $this->emailer->sendAdminNote($mail);
        }
        echo 1;

    }

    public function delete(Department $department){
        $department->messages()->delete();
        $department->delete();
        return redirect("departments")->with('notice',['class'=>'info','message'=>'Department Removed!']);
    }
}
