<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Sphere;
use App\Survey;
use App\Event;
use App\Vote;
use App\Task;
use App\User;
use Session;
use DB;
class VotesController extends Controller
{

  public function addVoteOnProjectInSphere(Request $req,$sphere_id=null,$project_id){
     $userSession=Session::get('sessionUser');
     if($userSession){
       if($sphere_id&&$project_id){
    $user=User::where(['email'=>Session::get('sessionUser')])->first();
    $userId=$user->id;
    $sphereId=$sphere_id;
    $founderSphere=DB::table('spheres')->where(['founder_id'=>$userId,'id'=>$sphereId])->count();
    $userJoinedRequestSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'request_joining_status'=>'accepted_status_request_joining'])->count();
    $userJoinedInvitationSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'invitation_status'=>'accepted_inivitation_status'])->count();
      if($founderSphere==0&&$userJoinedRequestSphere==0&&$userJoinedInvitationSphere==0){
    return view('spheres.go_into_sphere')->with(compact('userId','sphereId'));
    }else{
      $countProjectsSphere=Project::where(['sphere_id'=>$sphere_id])->count();
      if($req->isMethod('post')){
        $data=$req->all();
        $sessionUserEmail=  Session::get('sessionUser');
        $user=User::where(['email'=>$sessionUserEmail])->first();
        $countUserVotePro=  Vote::where(['user_id'=>$user->id,'project_id'=>$project_id,'sphere_id'=>$sphere_id])->count();
        $sphereThisProjects=Sphere::where(['id'=>$sphere_id])->first();
        $projectsSphere=Project::where(['sphere_id'=>$sphere_id])->get();
        if($countUserVotePro==0){
          $numUsersJoinRequestSphereAccepted=DB::table('sphere_users')->where(['sphere_id'=>$sphere_id,'request_joining_status'=>'accepted_status_request_joining'])->count();//contains on users and leaders this sphere
        $numUsersJoinInvitationSphereAccepted=DB::table('sphere_users')->where(['sphere_id'=>$sphere_id,'invitation_status'=>'accepted_inivitation_status'])->count();//contains on users and leaders this sphere
        $totalNumUsersInSphere=$numUsersJoinRequestSphereAccepted+$numUsersJoinInvitationSphereAccepted+1;//this num : 1 for founder this sphere
        if($totalNumUsersInSphere>=1){
          $project_num=0;
          $newVote=new Vote();
          $newVote->project_id=$project_id;
          $newVote->sphere_id=$sphere_id;
          $newVote->user_id=$user->id;
          $projectCount=Vote::where(['project_id'=>$project_id])->count();
          if($projectCount==0){
            $newVote->num_projects=1;
            }else{
              $num=$projectCount+1;
              $newVote->num_projects=$num;
            }
            $successSave= $newVote->save();
          return redirect('/view-details-project/'.$sphere_id.'/'.$project_id)->with('flash_message_success','add your vote suucessfully,please wait whole votes when it complete to see this project will be in arena of this sphere or not');
          }else{
          return redirect('/view-details-project/'.$sphere_id.'/'.$project_id)->with('flash_message_success','you cannt add your vote on this project , because this sphere not contains on enough num. users to be able voting on projects this sphere');
          } 
        }else{
          return redirect('/view-details-project/'.$sphere_id.'/'.$project_id)->with('flash_message_error','you not allow vote on this project , because you voted on it in prevouis time');
          }
      }
       }

     }else{
       return abort(404);    
     }
   }else{
  return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
 }
  }

    public function ProjectsInSphereToVotes($sphere_id=null){
      $userSession=Session::get('sessionUser');
      if($userSession){
        if($sphere_id){
            $user=User::where(['email'=>Session::get('sessionUser')])->first();
            $userId=$user->id;
            $sphereId=$sphere_id;
            $founderSphere=DB::table('spheres')->where(['founder_id'=>$userId,'id'=>$sphereId])->count();
            $userJoinedRequestSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'request_joining_status'=>'accepted_status_request_joining'])->count();
            $userJoinedInvitationSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'invitation_status'=>'accepted_inivitation_status'])->count();
              if($founderSphere==0&&$userJoinedRequestSphere==0&&$userJoinedInvitationSphere==0){
            return view('spheres.go_into_sphere')->with(compact('userId','sphereId'));
            }else{
              $sphereThisProjectsCount=Sphere::where(['id'=>$sphere_id])->count();
              if($sphereThisProjectsCount!==0){
               $sphereThisProjects=Sphere::where(['id'=>$sphere_id])->first(); 
              }
              
              $projectsSphereCount=Project::where(['sphere_id'=>$sphere_id])->count();
              if($projectsSphereCount!==0){
               $projectsSphere=Project::where(['sphere_id'=>$sphere_id])->get(); 
              }
              
              return view('votes.add_vote_on_project')->with(compact('sphereThisProjectsCount','projectsSphere','sphereThisProjects'));
          }
        
      }else{
        return abort(404);    
      }
      }else{
  return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    
      }

}

}