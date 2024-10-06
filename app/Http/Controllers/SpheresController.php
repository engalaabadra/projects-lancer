<?php

namespace App\Http\Controllers;

use App\Admin;
use Image;

use Illuminate\Http\Request;
use App\Sphere;
use App\Comment;
use App\Survey;
use App\Conversation;
use App\Event;
use App\Project;
use App\User;
use App\Task;
use DB;
use App\Post;
use Session;
use App\Http\Resources\PostResource as Posts;

class SpheresController extends Controller
{
  public function getMySpheres(){
    $sessionUser=Session::get('sessionUser');
    if($sessionUser){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $user_id=$user->id;
        $mySpheresFoundedCount=Sphere::where(['founder_id'=>$user_id])->count();
        if($mySpheresFoundedCount!==0){
          $mySpheresFounded=Sphere::where(['founder_id'=>$user_id])->get();
        }
        $mySpheresJoinedCount=DB::table('sphere_users')->where(['user_id'=>$user_id])->count();
        if($mySpheresJoinedCount){
        $mySpheresJoined=DB::table('sphere_users')->where(['user_id'=>$user_id])->get();
        }
        return view('spheres.get_my_spheres')->with(compact('mySpheresJoined','user','mySpheresFounded','mySpheresFoundedCount','mySpheresJoined','mySpheresJoinedCount'));
    }else{
    return redirect('user/login')->with('flash_message_error','you have not  authentication to enter into this page , please login ');
    }
  }
  public function getMyProjects(){
    $sessionUser=Session::get('sessionUser');
    if($sessionUser){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $user_id=$user->id;
        $mySpheresFoundedCount=Sphere::where(['founder_id'=>$user_id])->count();
        if($mySpheresFoundedCount!==0){
          $mySpheresFounded=Sphere::where(['founder_id'=>$user_id])->get();
        }
        $mySpheresJoinedCount=DB::table('sphere_users')->where(['user_id'=>$user_id])->count();
        if($mySpheresJoinedCount!==0){
          $mySpheresJoined=DB::table('sphere_users')->where(['user_id'=>$user_id])->get();
        }
        $myProjectsCreatedCount=DB::table('projects')->where(['user_id'=>$user_id])->count();
        if($myProjectsCreatedCount!==0){
          $myProjectsCreated=DB::table('projects')->where(['user_id'=>$user_id])->get();
        }
        $myProjectsJoinedCount=DB::table('projects_users')->where(['user_id'=>$user_id])->count();
        if($myProjectsJoinedCount!==0){
          $myProjectsJoined=DB::table('projects_users')->where(['user_id'=>$user_id])->get();
        }
        return view('spheres.get_my_projects')->with(compact('myProjectsJoinedCount','user','mySpheresFounded','mySpheresFoundedCount','mySpheresJoined','mySpheresJoinedCount','myProjectsCreated','myProjectsJoined','myProjectsCreatedCount'));
  }else{
    return redirect('user/login')->with('flash_message_error','you have not  authentication to enter into this page , please login ');
    }
  }
  
  public function createSphere(Request $req){
    $sessionUser=Session::get('sessionUser');
    if($sessionUser){
    $MainSpheresCount=Sphere::where(['parent_id'=>0])->count();
    if($MainSpheresCount!==0){
      $MainSpheres=Sphere::where(['parent_id'=>0])->get();
    }
 
    $allSpheresCount = Sphere::count();
    if($allSpheresCount!==0){
      $allSpheres = Sphere::get();
    }

    if($req->isMethod('post')){
      $data=$req->all();
      $parent_sphere=$data['parent_sphere'];
      $sphere=   Sphere::where(['name'=>$parent_sphere])->first();
      $user=User::where(['email'=>Session::get('sessionUser')])->first();
      $userId=$user->id;
      $sphereId=$sphere->id;
      $newSphere=new Sphere();
      $newSphere->parent_id=$sphereId;
      $newSphere->founder_id=$userId;
      $newSphere->name=$data['name_sphere'];
      $newSphere->description=$data['description_sphere'];
      $newSphere->primary_focus=$data['primary_focus'];
      //upload image
      if($req->hasFile('image_sphere')){
        $image_tmp=$req->file('image_sphere');
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
            $newSphere->image=$filename;
            $newSphere->save();
            return redirect('/user/my-spheres')->with('flash_message_success','add your sphere successfully');
       }
   }

  }
    return view('spheres.add_sub_sphere')->with(compact('allSpheres','MainSpheres','sphereId','MainSpheresCount'));
  }else{
    return redirect('user/login')->with('flash_message_error','you have not  authentication to enter into this page , please login ');
    }
  }

  public function ProjectsSphere(Request $req , $sphere_id){
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
        $getProjectsSphereInProgressCount=Project::where(['sphere_id'=>$sphere_id,'status_project'=>'in-progress'])->count();
        if($getProjectsSphereInProgressCount!==0){
            $getProjectsSphereInProgress=Project::where(['sphere_id'=>$sphere_id,'status_project'=>'in-progress'])->get();

        }
        $sphere=Sphere::where(['id'=>$sphere_id])->first();
        $nameSphere  = $sphere->name;
        $countProjectsSphere=Project::where(['sphere_id'=>$sphere_id])->count();
        if($countProjectsSphere!==0){
          $getProjectsSphere=Project::where(['sphere_id'=>$sphere_id])->get();
        }
        $getProjectsSphereCompletedCount=Project::where(['sphere_id'=>$sphere_id,'status_project'=>'completed'])->count();
        if($getProjectsSphereCompletedCount!==0){
          $getProjectsSphereCompleted=Project::where(['sphere_id'=>$sphere_id,'status_project'=>'completed'])->get();
        }
        $getProjectsSphereIamInItCount=DB::table('projects_users')->where(['user_id'=>$userId,'invitation_status'=>'accepted_inivitation_status'])->count();
        if($getProjectsSphereIamInItCount!==0){
          $getProjectsSphereIamInIt=DB::table('projects_users')->where(['user_id'=>$userId,'invitation_status'=>'accepted_inivitation_status'])->get();
        }              
        $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
        $user_id=$user->id;
        $mySpheresFoundedCount=Sphere::where(['founder_id'=>$user_id])->count();
        if($mySpheresFoundedCount!==0){
          $mySpheresFounded=Sphere::where(['founder_id'=>$user_id])->get(); 
        }
        
        $mySpheresJoinedCount=DB::table('sphere_users')->where(['user_id'=>$user_id])->count();
        if($mySpheresJoinedCount!==0){
          $mySpheresJoined=DB::table('sphere_users')->where(['user_id'=>$user_id])->get();
        }
        
        return view('spheres.projects_sphere')->with(compact('mySpheresFounded','mySpheresJoined','mySpheresFoundedCount','mySpheresJoinedCount','getProjectsSphereIamInItCount','getProjectsSphereCompletedCount','getProjectsSphereInProgressCount','sphereId','getPostsSphere','getProjectsSphereIamInIt','getProjectsSphereCompleted','getProjectsSphereInProgress','usersSphere','usersSphereCount','sphere','countUsersSphere','getProjectsSphere','sphere','countProjectsSphere','nameSphere','getPostsSphere'));
      }
    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authurization to access into this page');      
    }
  }

  public function PostsSphere($sphere_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
            $user=User::where(['email'=>Session::get('sessionUser')])->first();
            $userId=$user->id;
            $mySpheresCount=DB::table('spheres')->where(['founder_id'=>$userId])->count();
            if($mySpheresCount!==0){
              $mySpheres=DB::table('spheres')->where(['founder_id'=>$userId])->get();
            }
            
            $projectsSphereCount=Project::where(['sphere_id'=>$sphere_id])->count();
            if($projectsSphereCount!==0){
              $projectsSphere=Project::where(['sphere_id'=>$sphere_id])->get();
            }

            $tasksSphereCount=Task::where(['sphere_id'=>$sphere_id])->count();
            if($tasksSphereCount!==0){
              $tasksSphere=Task::where(['sphere_id'=>$sphere_id])->get();
            }
            
            $surveysSphereCount=Survey::where(['sphere_id'=>$sphere_id])->count();
            if($surveysSphereCount!==0){
              $surveysSphere=Survey::where(['sphere_id'=>$sphere_id])->get();
            }
            
            $eventsSphereCount=Event::where(['sphere_id'=>$sphere_id])->count();
            if($eventsSphereCount){
              $eventsSphere=Event::where(['sphere_id'=>$sphere_id])->get();
            }
            
            $conversationsSphereCount=Conversation::where(['sphere_id'=>$sphere_id])->count();
            if($conversationsSphereCount!==0){
              $conversationsSphere=Conversation::where(['sphere_id'=>$sphere_id])->get();
            }

            $userCount=DB::table('sphere_users')->where(['user_id'=>$user->id,'sphere_id'=>$sphere_id])->count();
            //will enter into page sphere in 2 cases : 1. in table sphere_users in same time his status : accepted 2 . in table sphere(he is founder)
            if($userCount!==0){//if this user is exist in sphere_users table (three cases for exist this user in this table: 1. request_joining_status 2. pending_inivitation_status 3. accepted_inivitation_status)
              $userCountRequestJoining=DB::table('sphere_users')->where(['user_id'=>$user->id,'sphere_id'=>$sphere_id,'request_joining_status'=>'accepted_status_request_joining'])->count();
              $userCountRequestPendingJoining=DB::table('sphere_users')->where(['user_id'=>$user->id,'sphere_id'=>$sphere_id,'request_joining_status'=>'pending_status_request_joining'])->count();

              if($userCountRequestJoining!==0){//request_joining_status:accepted
                $sphereId=$sphere_id;
                $getFollowersCount=DB::table('users_followers')->where(['user_id'=>$user->id])->count();
                $getFollowers=DB::table('users_followers')->where(['user_id'=>$user->id])->get();
                $sphere=Sphere::where(['id'=>$sphere_id])->first();
                $nameSphere  = $sphere->name;
                $getUsers=User::get();
                // $getPostsSphere=Post::where(['sphere_id'=>$sphere_id])->with(["user","comments"])->get();
                $user=User::where(['email'=>Session::get('sessionUser')])->first();
                $userId=$user->id;
                $sphereId=$sphere_id;
                return view('spheres.posts_spheres')->with(compact('sphereId','conversationsSphereCount','eventsSphereCount','surveysSphereCount','tasksSphereCount','projectsSphereCount','sphere','sphere_id','mySpheres','mySpheresCount','conversationsSphere','eventsSphere','surveysSphere','projectsSphere','tasksSphere','sphere_id','user','getFollowersCount','getFollowers','sphereId','getUsers','sphere','getProjectsSphere','sphere','nameSphere','getPostsSphere'));
              }elseif($userCountRequestPendingJoining!==0){//request_joining_status:pending
                return redirect('/user/my-spheres')->with('flash_message_error','you send request join into  sphere , pls, wait accept on your request');
              }else{//invitation status
              $userCountInvitation=DB::table('sphere_users')->where(['user_id'=>$user->id,'sphere_id'=>$sphere_id,'invitation_status'=>'pending_inivitation_status'])->count();
              $userCountAcceptedInvitation=DB::table('sphere_users')->where(['user_id'=>$user->id,'sphere_id'=>$sphere_id,'invitation_status'=>'accepted_inivitation_status'])->count();
              if($userCountInvitation!==0){//pending_inivitation_status
                return redirect('/user/view-profile/'.$user->email)->with('flash_message_error','you invitated into this sphere , pls ,from your invitation in your profile accept on request joining into this sphere to see it');
              }elseif($userCountAcceptedInvitation!==0){//accepted_inivitation_status
              $sphereId=$sphere_id;
              $getFollowersCount=DB::table('users_followers')->where(['user_id'=>$user->id])->count();
              $getFollowers=DB::table('users_followers')->where(['user_id'=>$user->id])->get();
              $sphere=Sphere::where(['id'=>$sphere_id])->first();
              $nameSphere  = $sphere->name;
              $getUsers=User::get();
              // $getPostsSphere=Post::where(['sphere_id'=>$sphere_id])->with(["user","comments"])->get();
              $user=User::where(['email'=>Session::get('sessionUser')])->first();
              $userId=$user->id;
              $sphereId=$sphere_id;
              return view('spheres.posts_spheres')->with(compact('sphereId','conversationsSphereCount','eventsSphereCount','surveysSphereCount','tasksSphereCount','projectsSphereCount','sphere','sphere_id','mySpheres','mySpheresCount','conversationsSphere','eventsSphere','surveysSphere','projectsSphere','tasksSphere','sphere_id','user','getFollowersCount','getFollowers','sphereId','getUsers','sphere','getProjectsSphere','sphere','nameSphere','getPostsSphere'));
              }else{
                $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
                $userId=$user->id;
                $sphereId=$sphere_id;
                return view('spheres.go_into_sphere')->with(compact('userId','sphereId'));

              }
            }
            }else{//if the user not exist in table sphere_user , so will check exist in table sphere or not , if yes will show this sphere 
              $founderCount=DB::table('spheres')->where(['founder_id'=>$user->id,'id'=>$sphere_id])->count();//if founder or not 
              if($founderCount!==0){
                $sphereId=$sphere_id;
                $getFollowersCount=DB::table('users_followers')->where(['user_id'=>$user->id])->count();
                $getFollowers=DB::table('users_followers')->where(['user_id'=>$user->id])->get();
                $sphere=Sphere::where(['id'=>$sphere_id])->first();
                $nameSphere  = $sphere->name;
                $getUsers=User::get();
                $userId=$user->id;
                $user=User::where(['email'=>Session::get('sessionUser')])->first();
                $userId=$user->id;
                $sphereId=$sphere_id;
                $sphere=sphere::where(['id'=>$sphere_id])->first();
                  return view('spheres.posts_spheres')->with(compact('founderCount','sphereId','sphere','sphere_id','mySpheres','mySpheresCount','conversationsSphere','eventsSphere','surveysSphere','projectsSphere','tasksSphere','userId','user','getFollowersCount','getFollowers','sphereId','getPostsSphere','getUsers','sphere','countUsersSphere','countLeadersSphere','getProjectsSphere','countProjectsSphere','nameSphere','getPostsSphere'));
              }else{
                //if no -> it is mean that : this user not join in sphere not user and not leader in sphere also not founder , so will not show this sphere , will show msg : you not join in this sphere pls , request to join in sphere 
                $user=User::where(['email'=>Session::get('sessionUser')])->first();
                $userId=$user->id;
                $sphereId=$sphere_id;
                return view('spheres.join_into_sphere')->with(compact('userId','sphereId'));
              }
          }
    }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    }
}
  public function requestJoiningIntoSphere($userId,$sphere_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
            $countJoin= DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphere_id,'request_joining_status'=>'pending_status_request_joining'])->count();
            if($countJoin==0){
              DB::table('sphere_users')->insert(['user_id'=>$userId,'sphere_id'=>$sphere_id,'request_joining_status'=>'pending_status_request_joining']);
              return view('spheres.success_request_joining_sphere')->with(compact('sphere_id'));
            }else{
              return view('spheres.error_request_joining_sphere')->with(compact('sphere_id'));
            }
    }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');

    }

  }
  
  public function viewDetailsRequestJoiningIntoSphere($sphere_id,$userId){
    $userSession=Session::get('sessionUser');
    if($userSession){
          $userId=$userId;
          $sphereCount= Sphere::where(['id'=>$sphere_id])->count();
          if($sphereCount){
            $sphere= Sphere::where(['id'=>$sphere_id])->first();
            $inivitCount=DB::table('sphere_users')->where(['user_id'=>$userId,'request_joining_status'=>'pending_status_request_joining','sphere_id'=>$sphere->id])->count();
  
            return view('user.view_details_request_join_sphere')->with(compact('sphere','userId','inivitCount','sphereCount'));
          }
    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    }
  }

  public function requestJoiningIntoProject($userId,$sphere_id,$project_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
      $countJoin= DB::table('projects_users')->where(['user_id'=>$userId,'sphere_id'=>$sphere_id,'project_id'=>$project_id,'request_joining_status'=>'pending_status_request_joining'])->count(); 
      if($countJoin==0){
          DB::table('projects_users')->insert(['user_id'=>$userId,'sphere_id'=>$sphere_id,'project_id'=>$project_id,'request_joining_status'=>'pending_status_request_joining']);
          return view('projects.success_request_joining_project');
        }else{
          return view('projects.error_request_joining_project');
        }
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      }
  }

  public function requestJoiningIntoTask($userId,$sphere_id,$task_id,$project_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
          $countJoin= DB::table('tasks_users')->where(['user_id'=>$userId,'sphere_id'=>$sphere_id,'task_id'=>$task_id,'project_id'=>$project_id,'request_joining_status'=>'pending_status_request_joining'])->count(); 
          if($countJoin==0){
              DB::table('tasks_users')->insert(['user_id'=>$userId,'sphere_id'=>$sphere_id,'task_id'=>$task_id,'request_joining_status'=>'pending_status_request_joining']);
              return redirect('/sphere/'.$sphere_id.'/project/'.$project_id.'/tasks')->with('flash_message_success','success your joining into task, pls wait accepting on your joining');
            }else{
              return redirect('/sphere/'.$sphere_id.'/project/'.$project_id.'/tasks')->with('flash_message_error','you cannot join again in this task , pls only wait accepting on your joining');
            }
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      }
  }
  public function requestJoiningIntoConversation($userId,$sphere_id,$conversation_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
          $countJoin= DB::table('conversations_users')->where(['user_id'=>$userId,'sphere_id'=>$sphere_id,'conversation_id'=>$conversation_id,'request_joining_status'=>'pending_status_request_joining'])->count();
          if($countJoin==0){
            DB::table('conversations_users')->insert(['user_id'=>$userId,'sphere_id'=>$sphere_id,'conversation_id'=>$conversation_id,'request_joining_status'=>'pending_status_request_joining']);
            return redirect('/sphere/'.$sphere_id.'/conversations')->with('flash_message_success','success your joining into conversation, pls wait accepting on your joining');
          }else{
            return redirect('/sphere/'.$sphere_id.'/conversations')->with('flash_message_error','you cannot join again in this conversation , pls only wait accepting on your joining');
          }
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      }
  }
  
  public function requestJoiningIntoEvent($userId,$sphere_id,$event_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
          $countJoin= DB::table('events_users')->where(['user_id'=>$userId,'sphere_id'=>$sphere_id,'event_id'=>$event_id,'request_joining_status'=>'pending_status_request_joining'])->count();
          if($countJoin==0){
              DB::table('events_users')->insert(['user_id'=>$userId,'sphere_id'=>$sphere_id,'event_id'=>$event_id,'request_joining_status'=>'pending_status_request_joining']);
              return view('events.success_request_joining_event');
            }else{
              return view('events.error_request_joining_event');
            }
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      }
  }
  
  public function viewDetailsRequestJoiningIntoProject($sphereId,$project_id,$userId){
    $userSession=Session::get('sessionUser');
    if($userSession){
          $userId=$userId;
          $sphere_id=$sphereId;
          $projectCount= Project::where(['id'=>$project_id,'sphere_id'=>$sphereId])->count();
          if($projectCount!==0){
           $project= Project::where(['id'=>$project_id,'sphere_id'=>$sphereId])->first(); 
           $user=User::where(['email'=>Session::get('sessionUser')])->first();
           $requestJoinProjectCount=DB::table('projects_users')->where(['user_id'=>$userId,'request_joining_status'=>'pending_status_request_joining','project_id'=>$project->id,'sphere_id'=>$sphere_id])->count();
           return view('user.view_details_request_join_project')->with(compact('user','requestJoinProjectCount','sphere_id','project','userId','projectCount'));
          }else{
            return redirect('/view-details-request-join-project/'.$sphereId.'/',$project_id.'/'.$userId)->with('flash_message_error','The data in this route is empty'); 

          }
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      }
  }
  public function viewDetailsRequestJoiningIntoTask($sphereId,$task_id,$userId,$project_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
      $userId=$userId;
      $sphere_id=$sphereId;
      $taskCount= Task::where(['id'=>$task_id,'sphere_id'=>$sphereId,'project_id'=>$project_id])->count();
      if($taskCount!==0){
        $task= Task::where(['id'=>$task_id,'sphere_id'=>$sphereId,'project_id'=>$project_id])->first();
        return view('user.view_details_request_join_task')->with(compact('sphere_id','task','userId','taskCount'));
      }else{
        return redirect('/view-details-request-join-task/'.$sphereId.'/'.$task_id.'/'.$userId.'/project/'.$project_id)->with('flash_message_error','The data in this route is empty'); 
        
      }
    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    }
  }
  public function viewDetailsRequestJoiningIntoConversation($sphereId,$conversation_id,$userId){
    $userSession=Session::get('sessionUser');
    if($userSession){
      $userId=$userId;
      $sphere_id=$sphereId;
      $conversation= Conversation::where(['id'=>$conversation_id,'sphere_id'=>$sphereId])->first();
      if(!empty($conversation)){

        return view('user.view_details_request_join_conversation')->with(compact('sphere_id','conversation','userId'));
      }else{
        return redirect('/view-details-request-join-conversation/'.$sphereId.'/'.$conversation_id.'/'.$userId)->with('flash_message_error','The data in this route is empty'); 
      }

    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    }
  }
  
  public function viewDetailsRequestJoiningIntoEvent($sphereId,$event_id,$userId){
    $userSession=Session::get('sessionUser');
    if($userSession){
      $userId=$userId;
      $sphere_id=$sphereId;
      $eventCount= Event::where(['id'=>$event_id,'sphere_id'=>$sphereId])->count();
      if($eventCount!==0){
        $requestJoineventCount=DB::table('events_users')->where(['user_id'=>$userId,'request_joining_status'=>'pending_status_request_joining','sphere_id'=>$sphereId])->count();
        $event= Event::where(['id'=>$event_id,'sphere_id'=>$sphereId])->first();
        return view('user.view_details_request_join_event')->with(compact('sphere_id','event','userId','eventCount','requestJoineventCount'));
      }else{
        return redirect('/view-details-request-join-event/'.$sphereId.'/'.$event_id.'/'.$userId)->with('flash_message_error','The data in this route is empty'); 
      }
    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    }
  }

  public function getPostSphere($sphere_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
      $getPostsSphereCount=Post::where(['sphere_id'=>$sphere_id])->with(["user","comments"])->count();
      if($getPostsSphereCount!==0){
        $getPostsSphere=Post::where(['sphere_id'=>$sphere_id])->with("user")->with("comments.user")->orderBy('created_at','desc')->paginate(5);
        return Posts::collection($getPostsSphere);
      }else{
        return redirect('/get-posts-sphere/'.$sphere_id)->with('flash_message_error','The data in this route is empty'); 
      }
    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    }
  }
  public function getAllSpheres(){
    $spheresCount=Sphere::where('parent_id','!=',0)->count();
    if($spheresCount){
      $spheres=Sphere::where('parent_id','!=',0)->get();
      return view('spheres.get_all_spheres')->with(compact('spheres','mainSpheres'));
    }else{
      return redirect('/view-all-spheres')->with('flash_message_error','The data in this route is empty'); 

    }
  }
  public function editSphere(Request $req, $sphere_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
      $sphere=Sphere::where(['id'=>$sphere_id])->first();
      $sphereId=$sphere->id;
      $MainSpheres=Sphere::where(['parent_id'=>0])->get();
      $user=User::where(['email'=>Session::get('sessionUser')])->first();
      $userId=$user->id;
      $data=$req->all();
      if($req->isMethod('post')){  
      $spheresCount=Sphere::where(['id'=>$sphere_id])->first();
      if($spheresCount!==0){
        $spheres=Sphere::where(['id'=>$sphere_id])->update(['name'=>$data['name'],'user_id'=>$userId,'description'=>$data['description']]);
      }
      return redirect('/sphere/'.$sphere_id.'/posts')->with('flash_message_success','edit success');
      }
      return view('spheres.edit_sphere')->with(compact('sphere','sphereId','MainSpheres'));

    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    }
  }

  public function editSphereByAdminOnly(Request $req, $sphere_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
      $sphere=Sphere::where(['id'=>$sphere_id])->first();
      $sphereId=$sphere->id;
      $MainSpheres=Sphere::where(['parent_id'=>0])->get();
      $admin=User::where(['email'=>Session::get('sessionAdmin')])->first();
      $adminId=$admin->id;
      $data=$req->all();
      if($req->isMethod('post')){
          
      $spheresCount=Sphere::where(['id'=>$sphere_id])->count();
      if($spheresCount!==0){
        $spheres=Sphere::where(['id'=>$sphere_id])->update(['name'=>$data['name'],'user_id'=>$adminId,'description'=>$data['description']]);
      }
      return redirect()->back()->with('flash_message_success','edit success');
      }
      return view('spheres.edit_sphere')->with(compact('sphere','sphereId','MainSpheres'));
    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    }
  }
  public function getSpheresForAdmin(){
    $adminSession=Session::get('sessionAdmin');
    if($adminSession){
      $spheresCount=Sphere::count();
      if($spheresCount!==0){
        $spheres=Sphere::get();
      }
      return view('admin.spheres.get_all_spheres_for_admin')->with(compact('spheres'));
    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    }
  }
  public function activateStatusSphere($sphere_id=null){
    $adminSession=Session::get('sessionAdmin');
    if($adminSession){
        $admin=Admin::where(['email'=>Session::get('sessionAdmin')])->first();
        $adminId=$admin->id;
        $sphere=Sphere::where(['id'=>$sphere_id])->update(['status_sphere'=>'activated_status']);
        return redirect()->back()->with('flash_message_success','activated sphere successfully');
    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    }
  }

  public function deActivateStatusSphere($sphere_id){
    $sessionAdmin=Session::get('sessionAdmin');
    if($sessionAdmin){
          $sphereCount=Sphere::where(['id'=>$sphere_id])->count();
          if($sphereCount){
            Sphere::where(['id'=>$sphere_id])->update(['status_sphere'=>'pending_status']);
          }
          return redirect()->back()->with('flash_message_success','deActivated sphere successfully');
    }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    }
  }

    public function editSphereImage(Request $req,$sphere_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
        if($req->isMethod('post')){
          if($req->hasFile('image_sphere')){
            $image_tmp=$req->file('image_sphere');
            if($image_tmp->isValid()){
              $extension=$image_tmp->getClientOriginalExtension();
              $filename=rand(111,9999).'.'.$extension;
              //save in folder
              $small_image_path='images/backend_images/spheres/small/'.$filename;
              //resize, save
              Image::make($image_tmp)->resize(300,300)->save(public_path($small_image_path));
              //store in db
              $editSphere=Sphere::where(['id'=>$sphere_id])->update(['image'=>$filename]);
              //  echo'kkk';die;
              return redirect('/sphere/'.$sphere_id.'/posts')->with('flash_message_success','edit image success');
            }
          }   
        }
    return view('spheres.edit_image_sphere')->with(compact('sphere_id'));
  }else{
  return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
  }
}

    public function editSphereCoverImage(Request $req,$sphere_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
        if($req->isMethod('post')){
          if($req->hasFile('cover_image_sphere')){
            $image_tmp=$req->file('cover_image_sphere');
            if($image_tmp->isValid()){
              $extension=$image_tmp->getClientOriginalExtension();
              $filename=rand(111,9999).'.'.$extension;
              //save in folder
              $small_image_path='images/backend_images/spheres/small/'.$filename;
              //resize, save
              Image::make($image_tmp)->resize(300,300)->save(public_path($small_image_path));
              //store in db
              $editSphere=Sphere::where(['id'=>$sphere_id])->update(['cover_image'=>$filename]);
              //  echo'kkk';die;
              return redirect('/sphere/'.$sphere_id.'/posts')->with('flash_message_success','edit cover image success');
            }
          }   
        }
          return view('spheres.edit_cover_image_sphere')->with(compact('sphere_id'));
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      }
    }

    public function getArenaSphere($sphere_id){
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
        $numUsersJoinRequestSphereAccepted=DB::table('sphere_users')->where(['sphere_id'=>$sphere_id,'request_joining_status'=>'accepted_status_request_joining'])->count();//contains on users and leaders this sphere
        $numUsersJoinInvitationSphereAccepted=DB::table('sphere_users')->where(['sphere_id'=>$sphere_id,'invitation_status'=>'accepted_inivitation_status'])->count();//contains on users and leaders this sphere
        $totalNumUsersInSphere=$numUsersJoinRequestSphereAccepted+$numUsersJoinInvitationSphereAccepted+1;//this num : 1 for founder this sphere
        if($totalNumUsersInSphere>=3){
          $sphereCountInArena=DB::table('arenas')->where(['sphere_id'=>$sphere_id])->count();
          if($sphereCountInArena==0){
            DB::table('arenas')->insert(['sphere_id'=>$sphere_id,'num_users'=>$totalNumUsersInSphere]);
            $projectCount= DB::table('projects')->where(['sphere_id'=>$sphere_id])->count();      
              if($projectCount!==0){
                DB::table('projects')->where(['sphere_id'=>$sphere_id])->update(['status_vote_project'=>'activated_vote']);      
              }
            //show arena
            return view('spheres.arenas')->with(compact('sphere_id'));
          }else{
          $arenaSphereCount= DB::table('arenas')->where(['sphere_id'=>$sphere_id])->count();
            if($arenaSphereCount!==0){
              DB::table('arenas')->where(['sphere_id'=>$sphere_id])->update(['num_users'=>$totalNumUsersInSphere]);
            }
            
            $projectCount= DB::table('projects')->where(['sphere_id'=>$sphere_id])->count();       
            if($projectCount!==0){
              DB::table('projects')->where(['sphere_id'=>$sphere_id])->update(['status_vote_project'=>'activated_vote']);       
            }
            //show arena
            return view('spheres.arenas')->with(compact('sphere_id'));
            }
        }else{
          $sphereArena=DB::table('arenas')->where(['sphere_id'=>$sphere_id])->count();
          //in case  decreas num users sphere
          if($sphereArena!==0){
            DB::table('arenas')->where(['sphere_id'=>$sphere_id])->delete();
          }
        $countProjectsActivateVotesSphere= DB::table('projects')->where(['sphere_id'=>$sphere_id,'status_vote_project'=>'activated_vote'])->count();
        if($countProjectsActivateVotesSphere!==0){
          DB::table('projects')->where(['sphere_id'=>$sphere_id])->update(['status_vote_project'=>'de_activated_vote']);
        }
        return redirect('/sphere/'.$sphere_id.'/posts')->with('flash_message_error','Arena Page of this sphere Not Available Now , because this sphere not contains enough number of members , so when it contains on the enough number of members , will show this Arena');
      }
    }
  }else{
  return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
  }
}

public function mySpheres(){
  $userSession=Session::get('sessionUser');
  if($userSession){
    $user=User::where(['email'=>Session::get('sessionUser')])->first();
    $user_id=$user->id;
    return view('spheres.my_spheres')->with(compact('user_id'));
  }else{
    return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
    } 
}
  public function createVoiceCall(){
    return view('voice.create_voice_call');
  }
  public function createVideoChat(){
  return  view('chats.video');
  }

  public function createRoomVideo(){
    view('rooms.create_room_video');
  }

  public function video(){
    return view('video.video');
    }
  }
