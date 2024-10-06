<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use App\User;
use App\Survey;
use App\Comment;
use App\Post;
use App\Event;
use App\Mention;
use App\Project;
use App\Conversation;
use App\Sphere;
use App\Category;
use App\Notifications\PostNewNotification;
use App\Task;
use DB;
use Session;
use Auth;
use Crypt;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\MustVerifyEmail; 
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;
use Laravel\Socialite\Two\InvalidStateException;
use Socialite;
use App\Notifications\NewMentionForUserNotify;

class UserController extends Controller
{
  //handle errors for empty data(404->empty db ,in same route) , error in link: in email or num. , invalid in route(404->route not found , in page 404) , session (401->unauth,login)
  public function getDataUser($email=null){
    $userSession=Session::get('sessionUser');
    if($userSession){
      if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // valid email
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $userId=$user->id;
        $userCount=User::where(['email'=>Session::get('sessionUser')])->count();
        $sessionUser=Session::get('sessionUser');
        $imagesUser=DB::table('backup_user_images')->where(['user_id'=>$user->id])->get();
        
        if($sessionUser==$email){
          $user_id=$user->id;
          $userCoverImage=$user->cover_image;
          $surveysFoundedCount=Survey::where(['user_id'=>$user_id])->count();
          if($surveysFoundedCount!==0){
            $surveysFounded=Survey::where(['user_id'=>$user_id])->get();
          }
          $spheresFoundedCount=Sphere::where(['founder_id'=>$user_id])->count();
          if($spheresFoundedCount!==0){
            $spheresFounded=Sphere::where(['founder_id'=>$user_id])->get();
          }
          $spheresJoinedCount=DB::table('sphere_users')->where(['user_id'=>$user_id])->count();
          if($spheresJoinedCount!==0){
            $spheresJoined=DB::table('sphere_users')->where(['user_id'=>$user_id])->get();
          }
          $projectsFoundedCount=Project::where(['user_id'=>$user_id])->count();
          if($projectsFoundedCount!==0){
            $projectsFounded=Project::where(['user_id'=>$user_id])->get();
          }
          $projectsJoinedCount=DB::table('projects_users')->where(['user_id'=>$user_id])->count();
          if($projectsJoinedCount!==0){
            $projectsJoined=DB::table('projects_users')->where(['user_id'=>$user_id])->get();
          }
          $projectsThisUserJoinedInItCount=DB::table('projects_users')->where(['user_id'=>$user->id])->count();
          if($projectsThisUserJoinedInItCount!==0){
            $projectsThisUserJoinedInIt=DB::table('projects_users')->where(['user_id'=>$user->id])->get();
          }
          $eventsThisUserJoinedInItCount=DB::table('events_users')->where(['user_id'=>$user->id])->count();
          if($eventsThisUserJoinedInItCount!==0){
            $eventsThisUserJoinedInIt=DB::table('events_users')->where(['user_id'=>$user->id])->get();
          }
          return view('user.get_all_data_user')->with(compact('user','userId','imagesUser','userCoverImage','projectsThisUserJoinedInIt','projectsThisUserJoinedInItCount','eventsThisUserJoinedInItCount','eventsThisUserJoinedInIt','spheresJoinedCount','projectsJoinedCount','projectsFoundedCount','spheresFoundedCount','surveysFoundedCount','surveysFounded','spheresFounded','spheresJoined','projectsFounded','projectsJoined'));
        }else{
          return redirect('/user/login')->with('flash_message_error','You can not see this page');
        }
    }else {
      //invalid link-> invalid email
      return abort(404);
    }
    }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    }

}
  public function getUserMentionComment($comment_id,$post_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
      if($comment_id&&$post_id){
          $userMention= Mention::where(['comment_id'=>$comment_id,'post_id'=>$post_id,'sphere_id'=>0])->with('user')->first();
          return response()->json([
          'status'=>200,
          'message'=>$userMention
          ]);

      }else{
        return response()->json([
          'status'=>404,
          'message'=>'comment , post is null'
      ]);
      }
    }else{
      return response()->json([
        'status'=>401,
        'message'=>'You have not authorization to access into this page'
    ]);
    }
  }
  public function getAllInvitations($userId){
    $userSession=Session::get('sessionUser');
    if($userSession){
      if($userId){
        $userCount=User::where(['id'=>$userId])->count();
        if($userCount!==0){
        $user=User::where(['id'=>$userId])->count();

          $userMentionsCount=DB::table('mentions')->where(['user_id'=>$userId])->count();
          if($userMentionsCount!==0){
            $userMentions=DB::table('mentions')->where(['user_id'=>$userId])->get();
          }
          $userSession=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
          $countUserSpheres=  DB::table('sphere_users')->where(['user_id'=>$userId])->count();
          if($countUserSpheres!==0){
            $UserSpheres=  DB::table('sphere_users')->where(['user_id'=>$userId])->get();
          }
          return view('user.get_all_invitations')->with(compact('user','countUserSpheres','UserSpheres','userId','user','userMentionsCount','userMentions','userSession'));
        }else{
          return redirect('/user/get-all-invitations/'.$userId)->with('flash_message_error','data in database is empty for this user');
        }
      }else{
        return abort(404);
      }
    }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
    }
  }
  public function getMemberName($memberId){
    $userSession=Session::get('sessionUser');
    if($userSession){
      $member=User::where(['id'=>$memberId])->first();
      $memberCount=User::where(['id'=>$memberId])->count();
      if($memberCount!==0){
        return response()->json([
          'status' => 200,
          'message' =>  $member
        ]);
      }else{
        return response()->json([
          'status' => 404,
          'message' =>  'data in database is empty for this member'
        ]);
      }

      }else{
        return response()->json([
          'status' => 401,
          'message' =>  'You have not authorization to access into this page'
        ]);   
      }     
  }

  public function getAllUsers(){
    $allUsersCount=DB::table('users')->count();
    $allUsers=DB::table('users')->get();
    if($allUsersCount!==0){
      return response()->json([
      'status' => 200,
      'message' =>  $allUsers
      ]);
    }else{
      return response()->json([
        'status' => 404,
        'message' =>  'there is no users in db'
        ]);
    }
  }
  public function searchData(Request $req){
    if($req->isMethod('post')){
      $data=$req->all();
      $searchUser=   User::where(['email'=>$data['text']])->orWhere(['username'=>$data['text']])->orWhere(['bio'=>$data['text']])->orWhere(['address'=>$data['text']])->orWhere(['job_title'=>$data['text']])->orWhere(['interests'=>$data['text']])->orWhere(['best_sentence'=>$data['text']])->get();
      $searchUserCount=   User::where(['email'=>$data['text']])->orWhere(['username'=>$data['text']])->orWhere(['bio'=>$data['text']])->orWhere(['bio'=>$data['text']])->orWhere(['address'=>$data['text']])->orWhere(['job_title'=>$data['text']])->orWhere(['interests'=>$data['text']])->orWhere(['best_sentence'=>$data['text']])->count();
      $searchTask=   Task::where(['name_task'=>$data['text']])->orWhere(['description_task'=>$data['text']])->get();
      $searchTaskCount=   Task::where(['name_task'=>$data['text']])->orWhere(['description_task'=>$data['text']])->count();
      $searchSurvey=   Survey::where(['title'=>$data['text']])->orWhere(['description'=>$data['text']])->get();
      $searchSurveyCount=   Survey::where(['title'=>$data['text']])->orWhere(['description'=>$data['text']])->count();
      $searchSphere=   Sphere::where(['name'=>$data['text'],['parent_id','!=',0]])->orWhere(['description'=>$data['text'],['parent_id','!=',0]])->orWhere(['primary_focus'=>$data['text'],['parent_id','!=',0]])->get();
      $searchSphereCount=   Sphere::where(['name'=>$data['text']])->orWhere(['description'=>$data['text']])->orWhere(['primary_focus'=>$data['text']])->count();
      $searchProject=   Project::where(['name'=>$data['text']])->orWhere(['description'=>$data['text']])->orWhere(['final_sentence'=>$data['text']])->get();
      $searchProjectCount=   Project::where(['name'=>$data['text']])->orWhere(['description'=>$data['text']])->orWhere(['final_sentence'=>$data['text']])->count();
      $searchEvent=   Event::where(['title'=>$data['text']])->orWhere(['description'=>$data['text']])->get();
      $searchEventCount=   Event::where(['title'=>$data['text']])->orWhere(['description'=>$data['text']])->count();
      $searchConversation=   Conversation::where(['title'=>$data['text']])->orWhere(['description'=>$data['text']])->get();
      $searchConversationCount=   Conversation::where(['title'=>$data['text']])->orWhere(['description'=>$data['text']])->count();
      $searchComment=   Comment::Where(['body'=>$data['text']])->get();
      $searchCommentCount=   Comment::Where(['body'=>$data['text']])->count();
      $searchPost=   Post::Where(['body'=>$data['text']])->get();
      $searchPostCount=   Post::Where(['body'=>$data['text']])->count();
      $searchCategory=   Category::where(['name'=>$data['text']])->orWhere(['name'=>$data['text']])->get();
      $searchCategoryCount=   Category::where(['name'=>$data['text']])->orWhere(['name'=>$data['text']])->count();
      return view('search.search')->with(compact('searchPostCount','searchPost','searchUser','searchUserCount','searchTaskCount','searchTask','searchSurveyCount','searchSurvey','searchSphereCount','searchSphere','searchProjectCount','searchProject','searchEventCount','searchEvent','searchConversationCount','searchConversation','searchCommentCount','searchComment','searchCategoryCount','searchCategory'));
    }
    return redirect()->back()->with('flash_message_error','please, you cannt open this page from here ');
  }
  public function storeUserInMentionTable(Request $req,$user_id,$comment_id,$post_id,$sphere_id=null){
    $userSession=Session::get('sessionUser');
    if($userSession){
      $userMention= new Mention();
      $userMention->sphere_id=$sphere_id;
      $userMention->user_id=$user_id;
      $userMention->comment_id=$comment_id;
      $userMention->post_id=$post_id;
      $userMention->save();
            //to get user that have the mention
        $userSession=User::where(['email'=>Session::get('sessionUser')])->first();
    //  if(intval($userSession->id)!==$user_id){
      $userMentionComment=User::where(['id'=>$user_id])->first();
      $userMentionComment->notify(new NewMentionForUserNotify($userMention));
    //  }
      return response()->json([
        'status' => 200,
        'message' =>$userMention
    ]);
    }else{
      return response()->json([
        'status' => 401,
        'message' =>  'You have not authorization to access into this page'
      ]);
    }     
  }

  public function updateUserInMentionTable(Request $req,$user_id,$comment_id,$post_id,$sphere_id=null){
    $userSession=Session::get('sessionUser');
    if($userSession){
      $userMention= Mention::where(['comment_id'=>$comment_id,'post_id'=>$post_id,'sphere_id'=>$sphere_id])->first();
      $userMention->user_id=$user_id;
      $userMention->save();
      
      return response()->json([
        'status' => 200,
        'message' =>$userMention
    ]);
    }else{
      return response()->json([
        'status' => 401,
        'message' =>  'You have not authorization to access into this page'
      ]);
    }     
  }
  
  public function showUser($emailUser){
    $userSession=Session::get('sessionUser');
    if($userSession){
      if(filter_var($emailUser, FILTER_VALIDATE_EMAIL)) {
        // valid email
        $user=DB::table('users')->where(['email'=>$emailUser])->first();
          return response()->json([
          'status' => 200,
          'message' => $user
        ]);
      }else {
        // invalid email
        return response()->json([
          'status' => 400,
          'message' => 'invalid email'
      ]);
      }
  }else{
    return response()->json([
      'status' => 401,
      'message' =>  'You have not authorization to access into this page'
    ]);
  }
}
  public function showEmailUser($idUser){
    $userSession=Session::get('sessionUser');
    if($userSession){
      $user=DB::table('users')->where(['id'=>$idUser])->first();
      $userCount=DB::table('users')->where(['id'=>$idUser])->count();
      if($userCount!==0){
        return response()->json([
        'status' => 200,
        'message' => $user
      ]);
      }else{
        return response()->json([
          'status' => 404,
          'message' =>  'data in database is empty for this user'
        ]);
      }

    }else{
      return response()->json([
        'status' => 401,
        'message' =>  'You have not authorization to access into this page'
      ]);   
    }  
  } 

  
  public function viewDetailsCommentPostMention($comment_id,$post_id,$user_id,$sphereId){
    $userSession=Session::get('sessionUser');
    if($userSession){
      $commentPost=DB::table('comments')->where(['id'=>$comment_id,'post_id'=>$post_id,'sphere_id'=>$sphereId,'user_id'=>$user_id])->first();
      $commentPostCount=DB::table('comments')->where(['id'=>$comment_id,'post_id'=>$post_id,'sphere_id'=>$sphereId,'user_id'=>$user_id])->count();
      return view('posts.details_comment_post')->with(compact('commentPost','comment_id','post_id','user_id','sphereId'));
    }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
    }
  }
  public function handleProviderCallbackGoogle()
  {
    $googleUser = Socialite::driver('google')->stateless()->user();
    $socialProviders=User::where('provider_id',$googleUser->getId())->first();
    if(!$socialProviders){
      //this user not exist in db , so add user in db
      $user=User::create([
        'email'=>$googleUser->getEmail(),
        'name'=>$googleUser->getUsername(),
        'provider_id'=>$googleUser->getId(),
        'provider'=>'facebook'
      ]);
      Session::put('sessionUser',$user->email);
      return redirect('/user/answer-on-questions');
    }
      return $socialProviders;
    }
  public function handleProviderCallbackGithub()
  {
    $githubUser = Socialite::driver('github')->stateless()->user();
      //this user not exist in db , so add user in db
      $socialProviders=User::where('provider', 'github')
      ->where('provider_id', $githubUser->getId())->first();
      if(!$socialProviders){
        $user=User::where('email', $githubUser->getEmail())->first();
        if($user){
          return redirect('/user/login');
        }
        $user=User::create([
          'email'=>$githubUser->getEmail(),
          'username'=>$githubUser->getName() ?? $githubUser->getNickname(),
          'provider_id'=>$githubUser->getId(),
          'provider'=>'github'
        ]);
        Session::put('sessionUser',$user->email);
        return redirect('/user/answer-on-questions');
      }
        Session::put('sessionUser',$socialProviders->email);
        return redirect('/user/answer-on-questions');
    }

    public function handleProviderCallbackFacebook()
    {
        try {
          $facebookUser = Socialite::driver('facebook')->user();
      } catch (InvalidStateException $e) {
          $facebookUser = Socialite::driver('facebook')->stateless()->user();
      }

        $socialProviders=User::where('provider_id',$facebookUser->getId())->first();
        if(!$socialProviders){
          //this user not exist in db , so add user in db
          $user=User::create([
            'email'=>$facebookUser->getEmail(),
            'name'=>$facebookUser->getName(),
            'provider_id'=>$facebookUser->getId(),
            'provider'=>'facebook'
          ]);
          Session::put('sessionUser',$user->email);
          return redirect('/user/answer-on-questions');
          //this user exist in db , so he can just login
        }
          return $socialProviders;
        //login user
      }
    public function redirectToProviderGithub()
    {
      return  Socialite::driver('github')->redirect();
    }
  public function redirectToProviderFacebook()
  {
    return  Socialite::driver('facebook')->redirect();
  }

  public function redirectToProviderGoogle()
  {
    return  Socialite::driver('google')
    ->with(
      ['client_id' => '760793908159-i3cni7n0qrmh1lctcg9mheb85nja0mq3.apps.googleusercontent.com'],
      ['client_secret' => 'Rw8iDXDfPfFWEu7vni8vR8pB'],
      ['redirect' => 'http://127.0.0.1:8000/user/auth/google/callback'])
    ->redirect();
  }

  ///////////////////////////////////
  public function regUser(Request $req){
    if(Session::get('sessionAdmin')){
      Session::forget('sessionAdmin');
    }
    if(Session::get('sessionUser')){
      User::where(['email'=>Session::get('sessionUser')])->update(['status_online'=>1]);
        return redirect('/user/dashboard');
    }
    if($req->isMethod('post')){
      $data=$req->all();
      $userCount=User::where('email',$data['email'])->count();
      if($userCount>0){
          return redirect('/user/login')->with('flash_message_error','email is exist already , so you can make  login direct');
      }else{
        if($data['password-repeat']==$data['password']){
        DB::table('users')->insert(['email'=>$data['email'],'password'=>encrypt($data['password']),'username'=>$data['username'],'status_reg_user'=>'pending']);
        return redirect('/user/answer-on-questions-reg')->with('flash_message_success','please , Answer these questions for your registration');
        }else{
          return redirect('/user/reg')->with('flash_message_error','password must be matching');
        }
      }
    }
    return view('user.reg_user');
  }

  public function loginUser(Request $req){
    if(Session::get('sessionAdmin')){
      Session::forget('sessionAdmin');
    }
    if(Session::get('sessionUser')){
      return redirect('/user/dashboard');
    }
    if($req->isMethod('post')){
      $data=$req->all();
      $userCount=User::where('email',$data['email'])->count();
      if($userCount==0){
        return redirect('/user/reg')->with('flash_message_error','email is not exist , you must make register');
      }else{
      $user=User::where('email',$data['email'])->first();
      if(decrypt($user->password)==$data['password']) {
        $user=User::where('email',$data['email'])->first();
        if($user->status_reg_user=='pending'){
          return redirect('/user/login')->with('flash_message_error','You can not enter into website , please wait activate your account , will reach a message into your email Soon'); 
        }elseif($user->status_reg_user=='activated'){
          Session::put('sessionUser',$data['email']);
          User::where(['email'=>Session::get('sessionUser')])->update(['status_online'=>1]);
          return redirect('/user/dashboard');
        }
      }else{
          return redirect('/user/login')->with('flash_message_error','invalid  password');
        }  
      }
    }
    return view('user.login_user');
  }


  public function logoutUser(){
    User::where(['email'=>Session::get('sessionUser')])->update(['status_online'=>0]);
    Session::forget('sessionUser');
    Session::forget('sessionUser');
    return redirect('/user/login');
  }

  public function forgotPassword(Request $req){
    if($req->isMethod('post')){
      $data=$req->all();
      $user=User::where(['email'=>$data['email']])->first();
      $userCount=User::where(['email'=>$data['email']])->count();
      if($userCount>0){
        $random_password=str_random(8);
        $new_password=encrypt($random_password);
        User::where(['email'=>$data['email']])->update(['password'=>$new_password]);
        //send email
        $email=$data['email'];
        $messageData=[
            'namey'=> $user->name,
            'email'=>$data['email'],
            'password'=>$random_password
        ];
        Mail::send('emails.enquiry_for_password',$messageData,function($message)use($email){
            $message->to($email)->subject('new pasword-ecommerce website');
        });
        $user=User::where('email',$data['email'])->first();
        $user->update(['status_online'=>0]);
      return redirect('/user/login')->with('flash_message_success','please , check your inbox email to get your new password');
      }else{
        return redirect('/user/reg')->with('flash_message_error','your email is not exsit , you do not make login  before this time , so you can register now');
      }
    }
    return view('user.forgot_password');
  }

  public function updatePassword(Request $req,$id=null){
    $userSession=Session::get('sessionUser');
    if($userSession){
      $userCount=User::where(['id'=>$id])->count();
      if($userCount!==0){
          $dataThisUser=User::where(['id'=>$id])->first(); 
        if($req->isMethod('post')){
          $req->validate([
              'old_password'=>'required',
              'new_password'=>'required'
              ]);
          $data=$req->all();
          $oldPassDb=$dataThisUser->password;
          $reqpass=$data['old_password'];
          $oldPassDbDec= decrypt($oldPassDb);
          if($reqpass==$oldPassDbDec){
            $newPassReq=encrypt($data['new_password']);
            DB::table('users')->where('id',$id)->update(['password'=>$newPassReq]);
            return redirect('/user/view-profile/'.$dataThisUser->email)->with('flash_message_success','your password updated successfully');
          }else{
            return redirect('/user/update-password/'.$dataThisUser->id)->with('flash_message_error','pls, write correct your old pass');
          }
        }
      return view('user.update_password')->with(compact('dataThisUser','dataThisUserCount'));
      }else{
        return redirect('/user/update-password/'.$id)->with('flash_message_error','This userId not exist');
      }
  }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
  }
}
public function changeImage($id=null,Request $req){
  $userSession=Session::get('sessionUser');
  if($userSession){
      $userCount=User::where(['id'=>$id])->count();
      if($userCount!==0){
        $dataThisUser=User::where(['id'=>$id])->first();
        $dataThisUserCount=User::where(['id'=>$id])->count();
        
        if($req->isMethod('post')){
            //upload image
            $data=$req->all();
            DB::table('backup_user_images')->insert(['image'=>$dataThisUser->image,'user_id'=>$dataThisUser->id]);
            if($req->hasFile('image')){
              $image_tmp=$req->file('image');
              if($image_tmp->isValid()){
                  $extension=$image_tmp->getClientOriginalExtension();
                $filename=rand(111,9999).'.'.$extension;
                  //save in folder
                  $image_path='images/backend_images/user/'.$filename;
                  //save in folder
                  $small_image_path='images/backend_images/user/small/'.$filename;
                  $medium_image_path='images/backend_images/user/medium/'.$filename;
                  $large_image_path='images/backend_images/user/large/'.$filename;
                  //resize, save
                  Image::make($image_tmp)->resize(300,300)->save(public_path($small_image_path));
                  //store in db
                  $editImg= User::where(['id'=>$id])->first();
                  $editImg->image=$filename;
                  $editImg->save();
                  return redirect('/user/view-profile/'.$dataThisUser->email)->with('flash_message_success','edit your image success');
              }
          }
        }
      return view('user.change_image_user')->with(compact('dataThisUser','dataThisUserCount'));
      }else{
        return redirect('/user/change-image/'.$id)->with('flash_message_error','The Data in this route is not exist');
      }
}else{
return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
}
}
  public function editProfile(Request $req,$id=null){
    $userSession=Session::get('sessionUser');
    if($userSession){
        $userCount=User::where(['id'=>$id])->count();
        if($userCount!==0){
          $dataThisUser=User::where(['id'=>$id])->first();
          $dataThisUserCount=User::where(['id'=>$id])->count();
          $userPassDb=$dataThisUser->password;
          $userPassDec=decrypt($userPassDb);
            if($req->isMethod('post')){

              $data=$req->all();
              DB::table('users')->where('id',$id)->update(['username'=>$data['username'],'job_title'=>$data['job_title'],'bio'=>$data['bio'],'address'=>$data['address'],'interests'=>$data['interests']]);
          }
          return view('user.edit_profile')->with(compact('dataThisUser','dataThisUserCount','userPassDec'));
        }else{
          return redirect('/user/edit-profile/'.$id)->with('flash_message_error','The Data in this route is not exist');
        }

  }else{
  return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
  }
}

  public function editUserCoverImage(Request $req,$userId){
    $userSession=Session::get('sessionUser');
    if($userSession){
        $userCount=User::where(['id'=>$userId])->count();
        if($userCount!==0){
          $dataThisUser=User::where(['id'=>$userId])->first();
          $dataThisUserCount=User::where(['id'=>$userId])->count();
          
          if($req->isMethod('post')){
            if($req->hasFile('cover_image_user')){
              $image_tmp=$req->file('cover_image_user');
              if($image_tmp->isValid()){
                $extension=$image_tmp->getClientOriginalExtension();
                $filename=rand(111,9999).'.'.$extension;
                //save in folder
                $small_image_path='images/backend_images/user/small/'.$filename;
                //resize, save
                Image::make($image_tmp)->resize(300,300)->save(public_path($small_image_path));
                //store in db
                $user=User::where(['id'=>$userId])->update(['cover_image'=>$filename]);
                //  echo'kkk';die;
                return redirect('/user/view-profile/'.$dataThisUser->email)->with('flash_message_success','edit cover image success');
              }
            }   
          }
        return view('user.edit_cover_image_user')->with(compact('user','dataThisUserCount'));
      }else{
        return redirect('/user/change-cover-image/'.$userId)->with('flash_message_error','The Data in this route is not exist');
      }

}else{
return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
} 
}

  public function cancelMyInvitationIntoSphere($user_id,$sphere_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
      DB::table('sphere_users')->where(['user_id'=>$user_id,'sphere_id'=>$sphere_id])->delete();
      return back();

    }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
    }
  }
  
  public function cancelMyInvitationIntoProject($user_id,$project_id,$sphere_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
      $project= DB::table('projects_users')->where(['user_id'=>$user_id,'project_id'=>$project_id,'sphere_id'=>$sphere_id])->first();
      if(!empty($project)){
        DB::table('projects_users')->where(['user_id'=>$user_id,'project_id'=>$project_id,'sphere_id'=>$sphere_id])->delete();
      return back();
      }else{
      return redirect('/cancel-my-invitation-into-project/'.$user_id.'/'.$project_id.'/'.$sphere_id)->with('flash_message_error','The data in this route is empty');
      }

    }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
    }
  }
  public function cancelMyInvitationIntoConversation($user_id,$conversation_id,$sphere_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
        $conv=  DB::table('conversations_users')->where(['user_id'=>$user_id,'conversation_id'=>$conversation_id,'sphere_id'=>$sphere_id])->first();
          if(!empty($conv)){
            DB::table('conversations_users')->where(['user_id'=>$user_id,'conversation_id'=>$conversation_id,'sphere_id'=>$sphere_id])->delete();
          return back();
          }else{
            return redirect('/cancel-my-invitation-into-conversation/'.$user_id.'/'.$conversation_id.'/'.$sphere_id)->with('flash_message_error','The Data in this route is not exist');
           }
    }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
    }
  }
  public function cancelMyInvitationIntoEvent($user_id,$event_id,$sphere_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
        $event=  DB::table('events_users')->where(['user_id'=>$user_id,'event_id'=>$event_id,'sphere_id'=>$sphere_id])->first();
        if(!empty($event)){
          DB::table('events_users')->where(['user_id'=>$user_id,'event_id'=>$event_id,'sphere_id'=>$sphere_id])->delete();
          return back();
        }else{
          return redirect('/cancel-my-invitation-into-event/'.$user_id.'/'.$event_id.'/'.$sphere_id)->with('flash_message_error','The data in this route is empty');
        }
    }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
    }
  }
  public function cancelMyRequestJoiningntoSphere($user_id,$sphere_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
         $sphere= DB::table('sphere_users')->where(['user_id'=>$user_id,'sphere_id'=>$sphere_id])->first();
         if(!empty($sphere)){
          DB::table('sphere_users')->where(['user_id'=>$user_id,'sphere_id'=>$sphere_id])->delete();
          return back(); 
         }else{
          return redirect('/cancel-my-request-joining-into-sphere/'.$user_id.'/'.$sphere_id)->with('flash_message_error','The data in this route is empty');

         }
    }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
    }
}
  public function cancelMyRequestJoiningntoConversation($user_id,$conversation_id,$sphere_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
        $conv=  DB::table('conversations_users')->where(['user_id'=>$user_id,'conversation_id'=>$conversation_id,'sphere_id'=>$sphere_id])->delete();
          if(!empty($conv)){
            DB::table('conversations_users')->where(['user_id'=>$user_id,'conversation_id'=>$conversation_id,'sphere_id'=>$sphere_id])->delete();
          return back();
          }else{
          return redirect('/cancel-my-request-joining-into-conversation/'.$user_id.'/'.$conversation_id.'/'.$sphere_id)->with('flash_message_error','The data in this route is empty');
          }
    }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
    }
  }

  public function cancelMyRequestJoiningntoEvent($user_id,$event_id,$sphere_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
        $event=  DB::table('events_users')->where(['user_id'=>$user_id,'event_id'=>$event_id,'sphere_id'=>$sphere_id])->first();
        if(!empty($event)){
          DB::table('events_users')->where(['user_id'=>$user_id,'event_id'=>$event_id,'sphere_id'=>$sphere_id])->delete();
          return back();
        }else{
          return redirect('/cancel-my-request-joining-into-event/'.$user_id.'/'.$event_id.'/'.$sphere_id)->with('flash_message_error','The data in this route is empty');

        }
    }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
    }
  }
  public function cancelMyRequestJoiningntoProject($user_id,$project_id,$sphere_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
        $project=  DB::table('projects_users')->where(['user_id'=>$user_id,'project_id'=>$project_id,'sphere_id'=>$sphere_id])->first();
        if(!empty($project)){
          DB::table('projects_users')->where(['user_id'=>$user_id,'project_id'=>$project_id,'sphere_id'=>$sphere_id])->delete();
          return back();
        }else{
          return redirect('/cancel-my-request-joining-into-project/'.$user_id.'/'.$project_id.'/'.$sphere_id)->with('flash_message_error','The data in this route is empty');
          
        }
    }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
    }
  }

  public function showAllUsers($sphereId){
    $userSession=Session::get('sessionUser');
    if($userSession){
          $sphere_id=$sphereId;
          $usersCount=User::count();
          if($usersCount){
            $users=User::get();
            return view('user.show_all_users')->with(compact('users','sphere_id'));
          }else{
            return redirect('/show-all-users/'.$sphereId)->with('flash_message_error','The data in this route is empty'); 
          }
    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
    }
  }
      public function invitationMember($user_id=null,$sphereid=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
          $UserJoinCount= DB::table('sphere_users')->where(['user_id'=>$user_id,'sphere_id'=>$sphereid])->count();
          if($UserJoinCount==0){
            DB::table('sphere_users')->insert(['user_id'=>$user_id,'invitation_status'=>'pending_inivitation_status','sphere_id'=>$sphereid]);
            return redirect()->back()->with('flash_message_success','success invitation sending');
          }else{
            return redirect()->back()->with('flash_message_error','you cant send inivitation , because you sent it into this member in prevuois time');
          }   
        }else{
        return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
        }
      }

      public function acceptInivitation($userid=null,$sphereid=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
             $sphere= DB::table('sphere_users')->where(['user_id'=>$userid,'sphere_id'=>$sphereid])->first();
              if(!empty($sphere)){
                DB::table('sphere_users')->where(['user_id'=>$userid,'sphere_id'=>$sphereid])->update(['invitation_status'=>'accepted_inivitation_status']);
                return redirect()->back()->with('flash_message_success','thanks on your accepting');
              }else{
            return redirect('/accept-inivitation/'.$userid.'/'.$sphereid)->with('flash_message_error','The data in this route is empty'); 
                
              }

        }else{
          return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
        }  
      }
      public function declineInivitation($user_id=null,$sphereid=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
          DB::table('sphere_users')->where(['user_id'=>$user_id,'sphere_id'=>$sphereid])->delete();
          return redirect()->back()->with('flash_message_error','you rejected the inivitiaon');
        }else{
          return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
        } 
      }
      public function acceptRequestJoining($userid=null,$sphereid=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
          DB::table('sphere_users')->where(['user_id'=>$userid,'sphere_id'=>$sphereid])->update(['request_joining_status'=>'accepted_status_request_joining']);
          return redirect()->back()->with('flash_message_success','you accepted  Request Joining into Sphere');
        }else{
          return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
        } 
      }
      public function declineRequestJoining($user_id=null,$sphereid=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
          DB::table('sphere_users')->where(['user_id'=>$user_id,'sphere_id'=>$sphereid])->delete();
          return redirect()->back()->with('flash_message_error','you rejected  Request Joining into Sphere');
        }else{
          return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
        } 
    }
      public function acceptRequestJoiningIntoProject($userid=null,$sphereid=null,$project_id=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
          DB::table('projects_users')->where(['user_id'=>$userid,'sphere_id'=>$sphereid,'project_id'=>$project_id])->update(['request_joining_status'=>'accepted_status_request_joining']);
          return redirect()->back()->with('flash_message_success','you accepted  Request Joining into project');
        }else{
          return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
        } 
      }
      public function acceptRequestJoiningIntoTask($userid=null,$sphereid=null,$task_id=null,$project_id){
        $userSession=Session::get('sessionUser');
        if($userSession){
          DB::table('tasks_users')->where(['user_id'=>$userid,'sphere_id'=>$sphereid,'task_id'=>$task_id,'project_id'=>$project_id])->update(['request_joining_status'=>'accepted_status_request_joining']);
          return redirect()->back()->with('flash_message_success','you accepted  Request Joining into task');
        }else{
          return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
        } 
      }
      public function declineRequestJoiningIntoTask($user_id=null,$sphereid=null,$task_id=null,$project_id){
        $userSession=Session::get('sessionUser');
        if($userSession){
          DB::table('tasks_users')->where(['user_id'=>$user_id,'sphere_id'=>$sphereid,'task_id'=>$task_id,'project_id'=>$project_id])->delete();
          return redirect()->back()->with('flash_message_error','you rejected  Request Joining into task');
        }else{
          return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
        } 
      }
      public function declineRequestJoiningIntoProject($user_id=null,$sphereid=null,$project_id=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
          DB::table('projects_users')->where(['user_id'=>$user_id,'sphere_id'=>$sphereid,'project_id'=>$project_id])->delete();
          return redirect()->back()->with('flash_message_error','you rejected  Request Joining into project');
        }else{
          return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
        } 
      }
      
      public function acceptRequestJoiningIntoConversation($userid=null,$sphereid=null,$conversation_id=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
          DB::table('conversations_users')->where(['user_id'=>$userid,'sphere_id'=>$sphereid,'conversation_id'=>$conversation_id])->update(['request_joining_status'=>'accepted_status_request_joining']);
          return redirect()->back()->with('flash_message_success','you accepted  Request Joining into conversation');
        }else{
          return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
        }      
      }

      public function declineRequestJoiningIntoConversation($user_id=null,$sphereid=null,$conversation_id=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
          DB::table('conversations_users')->where(['user_id'=>$user_id,'sphere_id'=>$sphereid,'conversation_id'=>$conversation_id])->delete();
          return redirect()->back()->with('flash_message_error','you rejected  Request Joining into conversation');
        }else{
          return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
        }  
      }

      public function acceptInivitationIntoProject($userid=null,$projectid=null,$sphereid=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
          DB::table('projects_users')->where(['user_id'=>$userid,'project_id'=>$projectid,'sphere_id'=>$sphereid])->update(['invitation_status'=>'accepted_inivitation_status']);
          return redirect()->back()->with('flash_message_success','thanks on your accepting');
        }else{
          return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
        }
      }
      public function acceptInivitationIntoTask($userid=null,$taskid=null,$sphereid=null,$project_id=null,$category_id){
        $userSession=Session::get('sessionUser');
        if($userSession){
         // dd($category_id);
        DB::table('tasks_users')->where(['category_id'=>$category_id,'user_id'=>$userid,'task_id'=>$taskid,'sphere_id'=>$sphereid,'project_id'=>$project_id])->update(['invitation_status'=>'accepted_inivitation_status']);
        return redirect()->back()->with('flash_message_success','thanks on your accepting');
        }
      }
      public function acceptInivitationIntoConversation($userid=null,$conversationid=null,$sphereid=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
        DB::table('conversations_users')->where(['user_id'=>$userid,'conversation_id'=>$conversationid,'sphere_id'=>$sphereid])->update(['invitation_status'=>'accepted_inivitation_status']);
        return redirect()->back()->with('flash_message_success','thanks on your accepting');
        }
      }
      
      public function declineInivitationIntoConversation($userid=null,$conversationid=null,$sphereid=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
        DB::table('conversations_users')->where(['user_id'=>$userid,'conversation_id'=>$conversationid,'sphere_id'=>$sphereid])->delete();
        return redirect()->back()->with('flash_message_error','you rejected the inivitiaon');        
        }
      } 
      public function declineInivitationIntoProject($userid=null,$projectid=null,$sphereid=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
        DB::table('projects_users')->where(['user_id'=>$userid,'project_id'=>$projectid,'sphere_id'=>$sphereid])->delete();
        return redirect()->back()->with('flash_message_error','you rejected the inivitiaon');
        }
      }
      public function declineInivitationIntoTask($userid=null,$taskid=null,$sphereid=null,$project_id,$category_id){
        $userSession=Session::get('sessionUser');
        if($userSession){
        DB::table('tasks_users')->where(['category_id'=>$category_id,'user_id'=>$userid,'task_id'=>$taskid,'sphere_id'=>$sphereid,'project_id'=>$project_id])->delete();
        return redirect()->back()->with('flash_message_error','you rejected the inivitiaon');
        }
      }
      public function acceptInivitationIntoEvent($userid=null,$eventid=null,$sphereid=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
        DB::table('events_users')->where(['user_id'=>$userid,'event_id'=>$eventid,'sphere_id'=>$sphereid])->update(['invitation_status'=>'accepted_inivitation_status']);
        return redirect()->back()->with('flash_message_success','thanks on your accepting');
        }
      }
      public function declineInivitationIntoEvent($userid=null,$eventid=null,$sphereid=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
        DB::table('events_users')->where(['user_id'=>$userid,'event_id'=>$eventid,'sphere_id'=>$sphereid])->delete();
        return redirect()->back()->with('flash_message_error','you rejected the inivitiaon');
        }
      }
      public function acceptRequestJoiningIntoEvent($userid=null,$sphereid=null,$event_id=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
        DB::table('events_users')->where(['user_id'=>$userid,'event_id'=>$event_id,'sphere_id'=>$sphereid])->update(['request_joining_status'=>'accepted_status_request_joining']);
        return redirect()->back()->with('flash_message_success','you accepted  Request Joining into event');
        }
      }
      public function declineRequestJoiningIntoEvent($userid=null,$sphereid=null,$event_id=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
        $member=DB::table('events_users')->where(['user_id'=>$userid,'event_id'=>$event_id,'sphere_id'=>$sphereid])->delete();
        return redirect()->back()->with('flash_message_error','you rejected  Request Joining into event');
        }
      }
      public function viewDetailsInivitationJoinSphere($sphereid=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
        $sphereCount= Sphere::where(['id'=>$sphereid])->count();
        if($sphereCount){
          $sphere= Sphere::where(['id'=>$sphereid])->first();
        }
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $inivitCount=DB::table('sphere_users')->where(['user_id'=>$user->id,'invitation_status'=>'pending_inivitation_status','sphere_id'=>$sphere->id])->count();
        return view('user.view_details_inivitation_join_sphere')->with(compact('sphere','sphereCount','user','inivitCount'));
      }
      }

      public function viewDetailsInivitationJoinConversation($conversation_id,$sphere_id){
        $userSession=Session::get('sessionUser');
        if($userSession){
          $conversationCount=Conversation::where(['id'=>$conversation_id,'sphere_id'=>$sphere_id])->count();
          if($conversationCount!==0){
            $conversation=Conversation::where(['id'=>$conversation_id,'sphere_id'=>$sphere_id])->first();
          }
          $user=User::where(['email'=>Session::get('sessionUser')])->first();
          $userId=$user->id;
          $requestJoinconversationCount=DB::table('conversations_users')->where(['user_id'=>$userId,'request_joining_status'=>'pending_status_request_joining','conversation_id'=>$conversation_id,'sphere_id'=>$sphere_id])->count();
          return view('user.view_details_inivitation_join_conversation')->with(compact('requestJoinconversationCount','userId','conversation','sphere_id','conversationCount','user','inivitCount'));
      }  
    }
      public function viewDetailsInivitationJoinProject($project_id,$sphere_id){
        $userSession=Session::get('sessionUser');
        if($userSession){
        $project=Project::where(['id'=>$project_id,'sphere_id'=>$sphere_id])->first();
        $projectCount=Project::where(['id'=>$project_id,'sphere_id'=>$sphere_id])->count();
        return view('user.view_details_inivitation_join_project')->with(compact('project','sphere_id','projectCount'));
        }
      }

      public function viewDetailsInivitationJoinTask($task_id,$sphere_id,$project_id,$category_id){
        $userSession=Session::get('sessionUser');
        if($userSession){
        $taskCount=Task::where(['id'=>$task_id,'sphere_id'=>$sphere_id,'project_id'=>$project_id,'category_id'=>$category_id])->count();
        if($taskCount!==0){
         $task=Task::where(['id'=>$task_id,'sphere_id'=>$sphere_id,'project_id'=>$project_id,'category_id'=>$category_id])->first(); 
         $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $inivitCount=DB::table('tasks_users')->where(['user_id'=>$user->id,'invitation_status'=>'status_pending','task_id'=>$task->id,'sphere_id'=>$sphere_id,'category_id'=>$category_id])->count();
        }


        return view('user.view_details_inivitation_join_task')->with(compact('task','taskCount','sphere_id','project_id','user','inivitCount'));
      }
      }
      public function viewDetailsInivitationJoinEvent($event_id,$sphere_id){
        $userSession=Session::get('sessionUser');
        if($userSession){
        $eventCount=Event::where(['id'=>$event_id,'sphere_id'=>$sphere_id])->count();
        if($eventCount!==0){
          $event=Event::where(['id'=>$event_id,'sphere_id'=>$sphere_id])->first();
        }
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $userId=$user->id;
        $requestJoineventCount=DB::table('events_users')->where(['user_id'=>$userId,'request_joining_status'=>'pending_status_request_joining','event_id'=>$event->id,'sphere_id'=>$sphere_id])->count();
        return view('user.view_details_inivitation_join_event')->with(compact('event','sphere_id','user','userId','eventCount','requestJoineventCount'));
      }
    }
      public function viewProfileMember($membername=null){
        $userSession=Session::get('sessionUser');
        if($userSession){
          
          $member= User::where(['email'=>$membername])->first();
          $memberEmail=$member->email;
          $imagesMember=DB::table('backup_user_images')->where(['user_id'=>$member->id])->get();
          $imagesMemberCount=DB::table('backup_user_images')->where(['user_id'=>$member->id])->count();
              $userCount= User::where(['email'=>$userSession])->count();
                $user= User::where(['email'=>$userSession])->first();
                $userId=$user->id;
              // dd($user);

              $spheresFoundedCount=Sphere::where(['founder_id'=>$member->id])->count();
              if($spheresFoundedCount){
                $spheresFounded=Sphere::where(['founder_id'=>$member->id])->get();
              }
              $spheresJoinedCount=DB::table('sphere_users')->where(['user_id'=>$member->id])->count();
              if($spheresJoinedCount){
                $spheresJoined=DB::table('sphere_users')->where(['user_id'=>$member->id])->get();
              }
              $projectsFoundedCount=Project::where(['user_id'=>$member->id])->count();
              if($projectsFoundedCount){
                $projectsFounded=Project::where(['user_id'=>$member->id])->get();
              }
              $projectsJoinedCount=DB::table('projects_users')->where(['user_id'=>$member->id])->count();
              if($projectsJoinedCount){
                $projectsJoined=DB::table('projects_users')->where(['user_id'=>$member->id])->get();
              }
              if($userSession==$memberEmail){
                abort(404);
              }else{
                $userCountStatusFollowing=DB::table('users_followers')->where(['follower_id'=>$member->id,'user_id'=>$user->id,'status_following'=>'accepted_status'])->count();
                if($userCountStatusFollowing!==0){
                $msgFollow='you can decline the following from here';
                return view('user.view_profile_member')->with(compact('imagesMemberCount','imagesMember','user','userCount','memberEmail','user','userId','msgFollow','member','projectsThisUserJoinedInIt','projectsThisUserJoinedInItCount','eventsThisUserJoinedInItCount','eventsThisUserJoinedInIt','spheresJoinedCount','projectsJoinedCount','projectsFoundedCount','spheresFoundedCount','surveysFoundedCount','surveysFounded','user_id','spheresFounded','spheresJoined','projectsFounded','projectsJoined','projectsFoundedCount'));
              }
              return view('user.view_profile_member')->with(compact('imagesMemberCount','imagesMember','user','userCount','member','memberEmail','user','userId','projectsThisUserJoinedInIt','projectsThisUserJoinedInItCount','eventsThisUserJoinedInItCount','eventsThisUserJoinedInIt','spheresJoinedCount','projectsJoinedCount','projectsFoundedCount','spheresFoundedCount','surveysFoundedCount','surveysFounded','user_id','spheresFounded','spheresJoined','projectsFounded','projectsJoined','projectsFoundedCount'));
              }
            
      }else{
        return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
      }
  }
      public function invitationIntoProject($member_id=null,$sphere_id=null,$project_id){
        $userSession=Session::get('sessionUser');
        if($userSession){
          DB::table('projects_users')->insert(['user_id'=>$member_id,'sphere_id'=>$sphere_id,'project_id'=>$project_id,'invitation_status'=>'status_pending']);
          return redirect()->back()->with('flash_message_success','your invitation sent into this member');
        }else{
          return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
        } 
      }
      public function invitationIntoTask($member_id=null,$sphere_id=null,$project_id,$task_id,$category_id){
        $userSession=Session::get('sessionUser');
        if($userSession){
              $memberTaskInvitCount=DB::table('tasks_users')->where(['id'=>$task_id,'sphere_id'=>$sphere_id,'project_id'=>$project_id,'category_id'=>$category_id])->count();
              // if($memberTaskInvitCount){
                $memberTaskInvit=DB::table('tasks_users')->where(['user_id'=>$member_id,'task_id'=>$task_id,'sphere_id'=>$sphere_id,'project_id'=>$project_id,'category_id'=>$category_id])->first();
                if(!empty($memberTaskInvit)){
                  if($memberTaskInvit->invitation_status=='status_pending'){
                    return response()->json([
                      'status'=>200,
                      'data'=>'wait'
                      ]);
                    }else{
                    if($memberTaskInvit->invitation_status=='accepted_inivitation_status'){
                      return response()->json([
                        'status'=>200,
                        'data'=>'accepted'
                        ]);
                  }
                }
                }else{
                  DB::table('tasks_users')->insert(['category_id'=>$category_id,'user_id'=>$member_id, 'sphere_id'=>$sphere_id,'task_id'=>$task_id,'project_id'=>$project_id,'invitation_status'=>'status_pending']);
                  return response()->json([
                    'status'=>200,
                    'data'=>'sent'
                    ]);
                }
              // }else{
              //   return response()->json([
              //     'status'=>404,
              //     'data'=>'there is no task for this user joined in it'
              //     ]);
              // }
          }else{
            return response()->json([
              'status' => 401,
              'message' =>  'You have not authorization to access into this page'
            ]);   
          }  
      }


      public function getDataTaskMember($member_id=null,$sphere_id=null,$project_id,$task_id,$category_id){
        $userSession=Session::get('sessionUser');
        if($userSession){
              $memberTaskInvitCount=DB::table('tasks_users')->where(['id'=>$task_id,'sphere_id'=>$sphere_id,'project_id'=>$project_id,'category_id'=>$category_id])->count();
              // if($memberTaskInvitCount){
                $memberTaskInvit=DB::table('tasks_users')->where(['user_id'=>$member_id,'task_id'=>$task_id,'sphere_id'=>$sphere_id,'project_id'=>$project_id,'category_id'=>$category_id])->first();
                if(!empty($memberTaskInvit)){
                  if($memberTaskInvit->invitation_status=='status_pending'){
                    return response()->json([
                      'status'=>200,
                      'data'=>'wait'
                      ]);
                    }else{
                    if($memberTaskInvit->invitation_status=='accepted_inivitation_status'){
                      return response()->json([
                        'status'=>200,
                        'data'=>'accepted'
                        ]);
                  }
                }
                }
              // }else{
              //   return response()->json([
              //     'status'=>404,
              //     'data'=>'there is no task for this user joined in it'
              //     ]);
              // }
          }else{
            return response()->json([
              'status' => 401,
              'message' =>  'You have not authorization to access into this page'
            ]);   
          }  
      }
      public function assignInTask($taskId,$member_id=null,$sphere_id=null,$project_id,$userId,$category_id){
        $userSession=Session::get('sessionUser');
        if($userSession){
              $memberTaskAssignedCount=DB::table('tasks')->where(['id'=>$taskId,'sphere_id'=>$sphere_id,'project_id'=>$project_id,'category_id'=>$category_id])->count();
              if($memberTaskAssignedCount!==0){
              $memberTaskAssigned=DB::table('tasks')->where(['id'=>$taskId,'sphere_id'=>$sphere_id,'project_id'=>$project_id,'category_id'=>$category_id])->first();
              if($memberTaskAssigned->status_task_invitation=='pending_status_task'){
                if($memberTaskAssigned->member_id==$member_id){
                  return response()->json([
                    'status'=>200,
                    'data'=>'pending_status_task_wait_his_accepting'
                    ]);
                }else{
                  return response()->json([
                    'status'=>200,
                    'data'=>'pending_status_task_must_be_a_task_for_a_member_just'
                    ]);
                }
              }else{
              if($memberTaskAssigned->status_task_invitation=='accepted_status_task'){
                if($memberTaskAssigned->member_id==$member_id){
                
                return response()->json([
                  'status'=>200,
                  'data'=>'accepted_status_task_already_for_this_member'
                  ]);

                }else{
                  return response()->json([
                    'status'=>200,
                    'data'=>'accepted_status_task_from_another_member_so_must_be_a_task_for_a_member_just'
                    ]);
                }
              }else{
                DB::table('tasks')->where(['id'=>$taskId])->update(['status_task'=>'','user_id'=>$userId,'member_id'=>$member_id,'sphere_id'=>$sphere_id,'project_id'=>$project_id,'status_task_invitation'=>'pending_status_task']);
                  return response()->json([
                    'status'=>200,
                    'data'=>'accepted_status_task_assigned_succefully_wait_his_accepting'
                    ]);
              } 
            }
          }else{
            return response()->json([
              'status' => 400,
              'message' =>  'There is no member assigned'
            ]);  
          }
    }else{
      return response()->json([
        'status' => 401,
        'message' =>  'You have not authorization to access into this page'
      ]);   
    } 
  }


  public function getAssignInTask($taskId,$member_id=null,$sphere_id=null,$project_id,$userId,$category_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
          $memberTaskAssignedCount=DB::table('tasks')->where(['id'=>$taskId,'sphere_id'=>$sphere_id,'project_id'=>$project_id,'category_id'=>$category_id])->count();
          if($memberTaskAssignedCount!==0){
          $memberTaskAssigned=DB::table('tasks')->where(['id'=>$taskId,'sphere_id'=>$sphere_id,'project_id'=>$project_id,'category_id'=>$category_id])->first();
          if($memberTaskAssigned->status_task_invitation=='pending_status_task'){
            if($memberTaskAssigned->member_id==$member_id){
              return response()->json([
                'status'=>200,
                'data'=>'pending_status_task_wait_his_accepting'
                ]);
            }else{
              return response()->json([
                'status'=>200,
                'data'=>'pending_status_task_must_be_a_task_for_a_member_just'
                ]);
            }
          }else{
          if($memberTaskAssigned->status_task_invitation=='accepted_status_task'){
            if($memberTaskAssigned->member_id==$member_id){
            
            return response()->json([
              'status'=>200,
              'data'=>'accepted_status_task_already_for_this_member'
              ]);

            }else{
              return response()->json([
                'status'=>200,
                'data'=>'accepted_status_task_from_another_member_so_must_be_a_task_for_a_member_just'
                ]);
            }
          }
        }
      }else{
        return response()->json([
          'status' => 400,
          'message' =>  'There is no member assigned'
        ]);  
      }
}else{
  return response()->json([
    'status' => 401,
    'message' =>  'You have not authorization to access into this page'
  ]);   
} 
}
      public function cancelAssignTask($taskId,$sphere_id,$project_id){
        DB::table('tasks')->where(['id'=>$taskId])->update(['category_id'=>1,'sphere_id'=>$sphere_id,'project_id'=>$project_id,'status_task'=>'canceled_task','status_task_invitation'=>'']);
        return response()->json([
          'status'=>200,
          'data'=>'you canceled assign this  task for this member'
      ]);
      }

      public function cancelInvitTask($taskId,$category_id,$sphere_id,$project_id,$user_id){
      DB::table('tasks_users')->where(['user_id'=>$user_id,'task_id'=>$taskId,'category_id'=>$category_id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->delete();
    //  $taskDeleted=   DB::table('tasks_users')->where(['id'=>$taskId,'category_id'=>$category_id,'user_id'=>0,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->first();
        // dump($taskDeleted);
        
        return response()->json([
          'status'=>200,
          'data'=>'canceled'
      ]);
      }
      public function invitationIntoEvent($member_id=null,$sphere_id=null,$event_id){
        DB::table('events_users')->insert(['user_id'=>$member_id,'sphere_id'=>$sphere_id,'event_id'=>$event_id,'invitation_status'=>'status_pending']);
        return redirect()->back()->with('flash_message_success','your invitation sent into this member');

      }
      public function invitationIntoConversation($member_id=null,$sphere_id=null,$conversation_id){
        DB::table('conversations_users')->insert(['user_id'=>$member_id,'sphere_id'=>$sphere_id,'conversation_id'=>$conversation_id,'invitation_status'=>'status_pending']);
        return redirect()->back()->with('flash_message_success','your invitation sent into this member');

      }
      
      public function confirmAccount($emailCode){
      if($emailCode){
        if(filter_var($emailCode, FILTER_VALIDATE_EMAIL)) {
          $emailU=base64_destatus($emailCode);
          $userCount=User::where('email',$emailU)->count();
          if($userCount>0){
              $userDetails=User::where('email',$emailU)->first();
              if($userDetails->status_confirmation=='confirmed'){
                  return redirect('/home-page')->with('flash_message_success','your email account is already confirmed');
              }else{
                $userDetailsUpdate=User::where('email',$emailU)->update(['status_confirmation'=>'confirmed']);
                  //send welcome into our web to email
                $userDetails=User::where('email',$emailU)->first();
                $emailU=base64_destatus($emailCode);
                $nameU=$userDetails->username;
                $messageData=['email'=>$emailU,'name'=>$nameU];
                Mail::send('emails.welcome',$messageData,function($message) use ($emailU){
                    $message->to($emailU)->subject('welcome to  e-com website');
                });
                Session::put('sessionUser',$emailU);
                return redirect('/user/dashboard')->with('flash_message_success','your email account is already activated');
              }
      
          }else{
              abort(404);
          }
        }else {
          // invalid email
          return response()->json([
            'status' => 400,
            'message' => 'invalid email'
        ]);
        }
      }else{
        return response()->json([
          'status' => 404,
          'message' => 'This route not exist'
        ]);
      }
    }
    public function AnswerOnQuestionsReg(Request $req){
      if($req->isMethod('post')){
        $data=$req->all();
        $validateData = $req->validate([
          'email'=>'required',
          'answer_first_question'=>'required',
          'id_third_question'=>'required',
      ]);
        $user=User::where(['email'=>$data['email']])->first();
        $answerQ1=DB::table('answers_on_questions')->insert(['answer'=>$data['answer_first_question'],'question_id'=>$data['id_first_question'],'user_id'=>$user->id]);
        $answerQ2=DB::table('answers_on_questions')->insert(['answer'=>$data['answer_second_question1'],'question_id'=>$data['id_second_question'],'user_id'=>$user->id]);
        $answerQ3=DB::table('answers_on_questions')->insert(['question_id'=>$data['id_third_question'],'user_id'=>$user->id,'option_id'=>$data['option_id']]);         
        return redirect('/user/answer-on-questions-reg')->with('flash_message_success','Thank you on your answers , please wait activate your account , we will send a message into your email Soon');
      
      }
      $questionsCount=DB::table('questions')->where(['reg_related'=>1])->count();
      if($questionsCount!==0){
        $questions=DB::table('questions')->where(['reg_related'=>1])->get();
        $FirstQuestion = $questions[0];
        $SecondQuestion = $questions[1];
        $ThirdQuestion = $questions[2];
      }
      
      $spheresCount=Sphere::where('parent_id','=',0)->count();
      if($spheresCount!==0){
        $spheres=Sphere::where('parent_id','=',0)->get();
      }
      
      $optionsRegForThirdQCount=DB::table('options_question')->where(['question_id'=>$ThirdQuestion->id])->count();
      if($optionsRegForThirdQCount!==0){
        $optionsRegForThirdQ=DB::table('options_question')->where(['question_id'=>$ThirdQuestion->id])->get();
      }
      return view('user.answer_on_questions_reg')->with(compact('questionsCount','spheresCount','optionsRegForThirdQCount','spheres','FirstQuestion','SecondQuestion','ThirdQuestion','optionsRegForThirdQ'));
    }

    public function AnswerOnQuestions(Request $req){
      if($req->isMethod('post')){
        $data=$req->all();
        $validateData = $req->validate([
          'answer_first_question'=>'required',
          'id_third_question'=>'required',
      ]);
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $answerQ1=DB::table('answers_on_questions')->insert(['answer'=>$data['answer_first_question'],'question_id'=>$data['id_first_question'],'user_id'=>$user->id]);
        $answerQ2=DB::table('answers_on_questions')->insert(['answer'=>$data['answer_second_question1'],'question_id'=>$data['id_second_question'],'user_id'=>$user->id]);
        $answerQ3=DB::table('answers_on_questions')->insert(['question_id'=>$data['id_third_question'],'user_id'=>$user->id,'option_id'=>$data['option_id']]);         
        return redirect('/user/answer-on-questions')->with('flash_message_success','Thank you on your answers , please wait activate your account , we will send a message into your email Soon');
      
      }
      $questionsCount=DB::table('questions')->where(['reg_related'=>1])->count();
      if($questionsCount!==0){
        $questions=DB::table('questions')->where(['reg_related'=>1])->get();
        $FirstQuestion = $questions[0];
        $SecondQuestion = $questions[1];
        $ThirdQuestion = $questions[2];
      }
      
      $spheresCount=Sphere::where('parent_id','=',0)->count();
      if($spheresCount!==0){
        $spheres=Sphere::where('parent_id','=',0)->get();
      }
      
      $optionsRegForThirdQCount=DB::table('options_question')->where(['question_id'=>$ThirdQuestion->id])->count();
      if($optionsRegForThirdQCount!==0){
        $optionsRegForThirdQ=DB::table('options_question')->where(['question_id'=>$ThirdQuestion->id])->get();
      }
      return view('user.answer_on_questions')->with(compact('questionsCount','spheresCount','optionsRegForThirdQCount','spheres','FirstQuestion','SecondQuestion','ThirdQuestion','optionsRegForThirdQ'));
    }
      public function followMember($user_id=null,$memberEmail){
        $userSession=Session::get('sessionUser');
        if($userSession){
                $member=User::where(['email'=>$memberEmail])->first();
                DB::table('users_followers')->insert(['follower_id'=>$user_id,'user_id'=>$member->id,'status_following'=>'pending_status']);
                return redirect()->back()->with('flash_message_success','Sent your following into this member , pls wait accept from his , will reach into your profile');
             
          }else{
            return redirect()->back()->with('flash_message_success','You have not authorization to access into this page');
          }     
      }

      public function acceptFollow($user_id,$memberId){
        $userSession=Session::get('sessionUser');
        if($userSession){
          DB::table('users_followers')->where(['user_id'=>$user_id,'follower_id'=>$memberId])->update(['status_following'=>'accepted_status']);
          $becomeFollower=DB::table('users_followers')->where(['user_id'=>$memberId,'follower_id'=>$user_id,'status_following'=>'accepted_status'])->first();
          $msgFollow='you can decline the following from here';
          return redirect()->back()->with(compact('msgFollow'));

      }else{
        return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
      }
      }

      public function declineFollowMember($user_id,$memberId){
        $userSession=Session::get('sessionUser');
        if($userSession){
            $userFollowerCount=DB::table('users_followers')->where(['user_id'=>$user_id,'follower_id'=>$memberId])->count();
              DB::table('users_followers')->where(['user_id'=>$user_id,'follower_id'=>$memberId])->delete();
              return redirect()->back()->with('flash_message_warning','You declined follwing this follwer in your page');
            
      }else{
        return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
      }
      }
      public function declineFollow($user_id,$memberId){
        $userSession=Session::get('sessionUser');
        if($userSession){
            $userFollowerCount=DB::table('users_followers')->where(['user_id'=>$user_id,'follower_id'=>$memberId])->count();
              DB::table('users_followers')->where(['user_id'=>$memberId,'follower_id'=>$user_id])->delete();
              return redirect()->back()->with('flash_message_warning','You declined follwing this follwer in your page');
           
        }else{
          return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
        }
      }

      public function changeImageCover($id=null,Request $req){
        $userSession=Session::get('sessionUser');
        if($userSession){
          $dataThisUserCount=User::where(['email'=>$userSession])->count();
          if($dataThisUserCount!==0){
            $dataThisUser=User::where(['email'=>$userSession])->first(); 
          }
          if($req->isMethod('post')){
              //upload image
              if($req->hasFile('image')){
                $image_tmp=$req->file('image');
              if($image_tmp->isValid()){
                  $extension=$image_tmp->getClientOriginalExtension();
                $filename=rand(111,9999).'.'.$extension;
                  //save in folder
                  $image_path='images/backend_images/user/'.$filename;
                  //save in folder
                    $small_image_path='images/backend_images/user/small/'.$filename;
                    //resize, save
                    Image::make($image_tmp)->resize(300,300)->save(public_path($small_image_path));
                    //store in db
                    $editImg= User::where(['id'=>$id])->first();
                    $editImg->cover_image=$filename;
                    $editImg->save();
                  return redirect('/user/view-profile/'.$dataThisUser->email)->with('flash_message_success','edit your cover image success');
              }
            }
          }
        return view('user.change_cover_image_user')->with(compact('dataThisUser','dataThisUserCount'));
    }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
    }
    }
     
    public function viewFollowersUser($email){
      $userSession=Session::get('sessionUser');
      if($userSession){
          if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $userCount=User::where(['email'=>$email])->count();
            if($userCount){
              $user=User::where(['email'=>$email])->first();
              $followers_user=DB::table('users_followers')->join('users','users.id','=','users_followers.user_id')->where("users.id",$user->id)->get();
              return view('user.view_profile_followers')->with(compact('followers_user','user'));
            }else{
              return redirect('/user/view-profile/'.$email.'/followers')->with('flash_message_error','The Data in this route is not exist');
            }
          }else{
            return redirect('/user/dashboard')->with('flash_message_error','This email must be valid');
          }
      }else{
        return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');      
      }
    }


    

  public function getDataTasksUserForCalender(){
    $userSession=Session::get('sessionUser');
    if($userSession){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $taskUserAssigned=  Task::where(['member_id'=>$user->id,'status_task_invitation'=>'accepted_status_task'])->with(['category','sphere'])->get();
      //here task time that the user assigned for his
      return response()->json([
        'status' => 200,
        'message' =>  $taskUserAssigned
      ]);

    }else{
      return response()->json([
      'status' => 401,
      'message' =>  'You have not authorization to access into this page'
      ]);
    }
  }

  public function getDataEventsUserForCalender(){
    $userSession=Session::get('sessionUser');
    if($userSession){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $userId=$user->id;
        $eventsJoinedUser=User::find($userId)->events()->get();//get all events that it for this user
        //here task time that the user assigned for his
        return response()->json([
          'status' => 200,
          'message' =>  $eventsJoinedUser
        ]);

    }else{
      return response()->json([
      'status' => 401,
      'message' =>  'You have not authorization to access into this page'
      ]);
    }
  }

}


