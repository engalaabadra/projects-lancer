<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use App\Admin;
use DB;
use App\User;
use Illuminate\Contracts\Auth\MustVerifyEmail; 
use Illuminate\Support\Facades\Mail;
class AdminController extends Controller
{

    public function loginAdmin(Request $req){
        Session::forget('sessionUser');
        if($req->isMethod('post')){
            $data=$req->all();
            $adminCount=Admin::where('email',$data['email'])->count();
            if($adminCount=0){
                return redirect()->back()->with('flash_message_error','email is exist already');
            }else{
            $admin=Admin::where('email',$data['email'])->first();
              if(decrypt($admin->password)==$data['password']) {
                  Session::put('sessionAdmin',$data['email']);
                  return redirect('/admin/view-users')->with('flash_message_success','login admin success');

              }else{

                  return redirect()->back()->with('flash_message_error','invalid  password');
              }
               
            }
          }
        return view('admin.login_admin');
    }
    public function regAdmin(Request $req){
        if(Session::get('sessionAdmin')){
          Session::forget('sessionUser');
  
      }
      if(Session::get('sessionAdmin')){
          return redirect('/admin/dashboard')->with('flash_message_success','you are login exactly');
         
  
      }
          if($req->isMethod('post')){
              $data=$req->all();
              $adminCount=Admin::where('email',$data['email'])->count();
              if($adminCount>0){
                  return redirect()->back()->with('flash_message_error','email is exist already');
              }else{
                  if($data['password-repeat']==$data['password']){
                  DB::table('admins')->insert(['email'=>$data['email'],'password'=>encrypt($data['password']),'name'=>$data['name']]);
                  Session::put('sessionAdmin',$data['email']);
           
                 return redirect('/admin/dashboard');
                  
              }else{
                  return redirect()->back()->with('flash_message_error','password must be match');
              }
              }
          }
          return view('admin.reg_admin');
      }
    public function logoutAdmin(){
        Auth::logout();
        Session::forget('sessionAdmin');
        return redirect('/admin/landing-page');
    }
    public function activateAccountUser($emailUser=null){
        if(Session::get('sessionAdmin')){

            $user=User::where(['email'=>$emailUser])->first();
            $activateUser=User::where(['email'=>$emailUser])->update(['status_reg_user'=>'activated']);
            // send confirmation email
            $email=$emailUser;
            $messageData=['email'=>$emailUser,'username'=>$user->username,'code'=>base64_encode($email)];
            Mail::send('emails.confirmation',$messageData,function($message) use ($email){
                $message->to($email)->subject('confirm your  e-com account');
            });
            return redirect()->back()->with('flash_message_success','acivated user successfully');
        }else{
            return redirect()->back()->with('flash_message_error','you not auth');
        }

    }
    public function deActivateAccountUser($emailUser=null){
        if(Session::get('sessionAdmin')){
            $user=User::where(['email'=>$emailUser])->first();

            $email=$emailUser;
            $messageData=['email'=>$emailUser,'username'=>$user->username,'code'=>base64_encode($email)];
            Mail::send('emails.not_confirmation',$messageData,function($message) use ($email){
                $message->to($email)->subject('Your Account is not confirmed in   TWT website');
            });
        $user=User::where(['email'=>$emailUser])->delete();
        return redirect()->back()->with('flash_message_success','Your Proccess successfully');

    }else{
        echo 'you not auth';
    }
    }
    public function viewUsers(){
        if(Session::get('sessionAdmin')){
        $users=User::get();
        return view('admin.view_users')->with(compact('users'));
        }else{
            echo 'you not auth';
        }
    }
    public function showAnswersUser($userid=null){

        if(Session::get('sessionAdmin')){
        $answersThisUserCount=DB::table('answers_on_questions')->where(['user_id'=>$userid])->count();
        $answersThisUser=DB::table('answers_on_questions')->where(['user_id'=>$userid])->get();
        $user=User::where(['id'=>$userid])->first();
        $userName=$user->username;
        foreach($answersThisUser as $answerThisUser){
          $option=DB::table('options_question')->where(['id'=>$answerThisUser->option_id])->first();
        }

        return view('admin.show_answers_users')->with(compact('answersThisUserCount','answersThisUser','userName','user'));
        }else{
            echo 'you not auth';
        }
    }
    
}
