<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',[
           'only' => ['edit','update']
        ]);

        $this->middleware('guest',[
           'only'=>['create']
        ]);
    }

    public function create(){
        return view('users.create');
    }

    public function show($id){
        $user = User::findOrFail($id);
        $statuses = $user->statuses()->orderBy('created_at','desc')->paginate(30);
        return view('users.show',compact('user','statuses'));
    }

    public function store(Request $request){

        $rule = [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required'
        ];

        $this->validate($request,$rule);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');

//        Auth::login($user);
//
//        session()->flash('success','Hello!~ 您将在这里开启一段新的旅程~');
//        return redirect()->route('users.show',[$user]);
    }


    public function edit($id){
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        return view('users.edit',compact('user'));
    }

    public function update($id, Request $request){

        $this->validate($request,[
            'name' => 'required|max:50',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);
        session()->flash('success','个人资料更新成功！');
        return redirect()->route('users.show',$id);
    }

    public function index(){
        $users = User::paginate(10);
        //$users = $users->toArroy();
        //dd($users);
        return view('users.index',compact('users'));
    }

    /*
     * 管理员删除 用户
     */
    public function destroy($id){

        $user = User::findOrFail($id);
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success','删除用户成功');
        return back();

    }


    /*
     * 激活用户发送邮件
     */
    protected function sendEmailConfirmationTo($user){

        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'aufree@estgroupe.com';
        $name = 'Aufree';
        $to = $user->email;
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    /**
     * 激活用户
     */
    public function confirmEmail($token){
        $user = User::where('activation_token',$token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜你.激活成功');
        return redirect()->route('users.show',[$user]);
    }



}
