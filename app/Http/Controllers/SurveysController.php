<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\Project;
use App\Sphere;
use App\Task;
use App\Event;
use App\User;
use DB;
use Session;
class SurveysController extends Controller
{
    public function getSurveys($sphere_id=null){
      $userSession=Session::get('sessionUser');
      if($userSession){
          $user=User::where(['email'=>Session::get('sessionUser')])->first();
          $userId=$user->id;
          $sphereId=$sphere_id;
          $founderSphere=DB::table('spheres')->where(['founder_id'=>$userId,'id'=>$sphereId])->count();
          $userJoinedRequestSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'request_joining_status'=>'accepted_status_request_joining'])->count();
          $userJoinedInvitationSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'invitation_status'=>'accepted_inivitation_status'])->count();
            if($founderSphere==0&&$userJoinedRequestSphere==0&&$userJoinedInvitationSphere==0){
          return view('spheres.go_into_sphere')->with(compact('userId','sphereId'));
          }else{
            $surveys=Survey::where(['sphere_id'=>$sphere_id])->get();
            $surveysCount=Survey::where(['sphere_id'=>$sphere_id])->count();
            return view('surveys.get_surveys')->with(compact('surveys','sphere_id','surveysCount'));
          }
    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    } 
    }

    public function answerSurvey($survey_id=null,$sphere_id=null,Request $req){
      $userSession=Session::get('sessionUser');
      if($userSession){
        if($survey_id&&$sphere_id){
            $survey=Survey::Where(['id'=>$survey_id])->first();
            $user=User::where(['email'=>Session::get('sessionUser')])->first();
            $userCreatedSurvey=$survey->user_id;
            if($userCreatedSurvey!==$user->id){
              $data=$req->all();
              $countUserSurvey=  DB::table('survey_users')->where(['user_id'=>$user->id,'sphere_id'=>$sphere_id,'survey_id'=>$survey_id])->count();
              // dd($countUserSurvey);
              if($countUserSurvey==0){
                $newSurvey=DB::table('survey_users')->insert(['survey_id'=>$survey_id,'sphere_id'=>$sphere_id,'user_id'=>$user->id,'answer_user'=>$data['answer_user']]);
                return redirect('/sphere/'.$sphere_id.'/surveys')->with('flash_message_success','thank you on your answer on srvery ');
              }else{
                // dd(1111);
                return redirect('/sphere/'.$sphere_id.'/surveys')->with('flash_message_error','you cannt add your answer in this survey , because you answered on it in prevoius time');
              }
            }else{
              return redirect('/sphere/'.$sphere_id.'/surveys')->with('flash_message_error','you cannt add your answer in this survey , because you created this survey');
            }

      }else{
      return abort(404);
      }
    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    } 
    }

    public function addSurveyUser(Request $req,$sphere_id=null){
      $userSession=Session::get('sessionUser');
      if($userSession){
            if($req->isMethod('post')){
              $data=$req->all();
              $user=User::where(['email'=>Session::get('sessionUser')])->first(); 
              $newSurvey=Survey::insert(['user_id'=>$user->id,'sphere_id'=>$sphere_id,'title'=>$data['title'],'description'=>$data['description'],'first_option'=>$data['first_option'],'second_option'=>$data['second_option']]);
              return redirect('/sphere/'.$sphere_id.'/surveys')->with('flash_message_success','add your survey successfully');
            }
            return view('surveys.add_survey')->with(compact('sphere_id'));
    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    } 
    }
    public function getAllAnswersSurvey($sphere_id,$survey_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
            $answersSurveyCount=DB::table('survey_users')->where(['sphere_id'=>$sphere_id,'survey_id'=>$survey_id])->count();
              $answersSurvey=DB::table('survey_users')->where(['sphere_id'=>$sphere_id,'survey_id'=>$survey_id])->get();
              return view('surveys.get_all_answers_survey')->with(compact('answersSurvey','answersSurveyCount'));
            
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
    }
    public function getSurveysJoinedIt(){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $surveysJoinedItCount= DB::table('survey_users')->where(['user_id'=>$user->id])->count();
        if($surveysJoinedItCount!==0){
          $surveysJoinedIt= DB::table('survey_users')->where(['user_id'=>$user->id])->get();
        }
        $mySpheresFoundedCount=Sphere::where(['founder_id'=>$user->id])->count();
        if($mySpheresFoundedCount!==0){
         $mySpheresFounded=Sphere::where(['founder_id'=>$user->id])->get(); 
        }
        
        $mySpheresJoinedCount=DB::table('sphere_users')->where(['user_id'=>$user->id])->count();
        if($surveysJoinedItCount!==0){
         $mySpheresJoined=DB::table('sphere_users')->where(['user_id'=>$user->id])->get(); 
        }
        
        return view('surveys.get_surveys_joined_it')->with(compact('mySpheresJoinedCount','mySpheresFoundedCount','mySpheresFounded','mySpheresJoined','surveysJoinedIt','surveysJoinedItCount','user'));

    }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    } 
    }
    public function deleteMySurvey($sphere_id,$survey_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
        dd(000);
           $survey= Survey::where(['id'=>$survey_id,'sphere_id',$sphere_id])->first();
           if($survey->delete()){
            return redirect('/sphere/'.$sphere_id.'/surveys')->with('flash_message_success','your survey deleted succefully');

           }
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
    }
    public function deleteMyAnswerOnSurvey($sphere_id,$survey_id,$user_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
             DB::table('survey_users')->where(['sphere_id'=>$sphere_id,'survey_id'=>$survey_id,'user_id'=>$user_id])->delete();
            return redirect('/sphere/'.$sphere_id.'/surveys')->with('flash_message_success','your answer on this survey deleted succefully');
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
    }
}

