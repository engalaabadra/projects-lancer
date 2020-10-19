<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\User;
use App\Vote;
use App\Survey;
use App\Event;
use App\Conversation;
use App\Project;
use App\Comment;
use App\Sphere;
use DB;
use Session;

class TaskController extends Controller
{
  public function getCategoriesTask($category_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
          $categoriesTask=Task::where(['category_id'=>$category_id])->get();
          return response()->json([
            'status' => 200,
            'message' =>  $categoriesTask
        ]);
    }else{
    return response()->json([
      'status' => 404,
      'message' =>  'You have not authorization to access into this page'
    ]);
    }
  }

  public function getTaskData($task_id){
    $userSession=Session::get('sessionUser');
    if($userSession){
          $taskData=Task::where(['id'=>$task_id])->first();
          return response()->json([
            'status' => 200,
            'message' =>  $taskData
        ]);
    }else{
    return response()->json([
      'status' => 404,
      'message' =>  'You have not authorization to access into this page'
  ]);
    }
  }
    public function getTasksProject($sphereId,$projectId){
      $userSession=Session::get('sessionUser');
      if($userSession){
        
              $user=User::where(['email'=>Session::get('sessionUser')])->first();
              $userId=$user->id;
              $founderSphereCount=DB::table('spheres')->where(['founder_id'=>$userId,'id'=>$sphereId])->count();
              $founderSphere=DB::table('spheres')->where(['founder_id'=>$userId,'id'=>$sphereId])->get();
              
              $leadersSphereAcceptedRequest=DB::table('sphere_users')->where(['sphere_id'=>$sphereId,'request_joining_status'=>'accepted_status_request_joining','is_lead'=>1])->get();
              $leadersSphereAcceptedRequestCount=DB::table('sphere_users')->where(['sphere_id'=>$sphereId,'request_joining_status'=>'accepted_status_request_joining','is_lead'=>1])->count();
              $leadersSphereAcceptedInvitation=DB::table('sphere_users')->Where(['sphere_id'=>$sphereId,'invitation_status'=>'accepted_inivitation_status','is_lead'=>1])->get();
              $leadersSphereAcceptedInvitationCount=DB::table('sphere_users')->Where(['sphere_id'=>$sphereId,'invitation_status'=>'accepted_inivitation_status','is_lead'=>1])->count();
              $userJoinedRequestSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'request_joining_status'=>'accepted_status_request_joining'])->count();
              $userJoinedInvitationSphere=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$sphereId,'invitation_status'=>'accepted_inivitation_status'])->count();

              if($founderSphereCount==0&&$userJoinedRequestSphere==0&&$userJoinedInvitationSphere==0){
                return view('spheres.go_into_sphere')->with(compact('userId','sphereId','userJoinedInvitationSphere'));
              }else{
                $sphere_id=$sphereId;
                $project_id=$projectId;
                $user_id=$user->id;
                return view('tasks.get_tasks_project')->with(compact('user_id','sphere_id','project_id','leadersSphereAcceptedRequestCount','leadersSphereAcceptedRequest','leadersSphereAcceptedInvitationCount','leadersSphereAcceptedInvitation','founderSphere','founderSphereCount','userJoinedInvitationSphere'));
              }
            
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      }
  }
    public function index()
    {
        return response()->json(Task::all()->toArray());
        
    }
    public function store(Request $request,$category_id,$user_id,$sphereId,$projectId)
    {
      $userSession=Session::get('sessionUser');
      if($userSession){
            $task=new Task();
            $task->name_task=$request->input('name');
            $task->category_id=$category_id;
            $task->user_id=$user_id;
            $task->sphere_id=$sphereId;
            $task->project_id=$projectId;
            $task->save();
            return response()->json([
              'status' => 200,
              'message' => $task
          ]);

      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      }
    }
    public function show(Task $task)
    {
        return response()->json($task);
    }

    public function update(Request $request, $id,$category_id,$user_id)
    {
      $userSession=Session::get('sessionUser');
      if($userSession){
        if($user_id&&$category_id){
      if($request->isMethod('put')){
        $task=Task::findOrFail($id);
        $task->category_id=$category_id;
        $task->user_id=$user_id;
        $task->name_task=$request->name;
        $task->save();
        return response()->json([
            'status' => '200',
            'message' => $task
        ]);

        }
    }else{
    return abort(404);
    }
  }else{
  return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
  }
    }
    public function destroy($id,$user_id)
    {
      $userSession=Session::get('sessionUser');
      if($userSession){
            $task =Task::where(['id'=>$id])->delete();

            return response()->json([
              'status'=>'delete successfully',
              'status'=>200,
              'data'=>$task
            ]);
           
        
      }else{
      return response()->json([
        'message'=>'You have not authorization to access into this page',
        'status'=>404
      ]); 
      }
    }

    public function getAllTasksSphere($sphere_id=null,$project_id=null){
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
              $sphereThisProjects=Sphere::where(['id'=>$sphere_id])->first();
              $nameSphereThisProjects  = $sphereThisProjects->name;
              $getProjectsSphere=Project::where(['sphere_id'=>$sphere_id])->get();
              $countProjectsSphere=Project::where(['sphere_id'=>$sphere_id])->count();
              $countTasksSphere=Task::where(['sphere_id'=>$sphere_id])->count();
              $getPostsSphere=Post::where(['sphere_id'=>$sphere_id])->get();
              return view('tasks.get_tasks_on_project_sphere')->with(compact('sphereThisProjects','countUsersSphere','countLeadersSphere','countTasksSphere','getProjectsSphere','sphereThisProjects','countProjectsSphere','nameSphereThisProjects','getPostsSphere'));
          }
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
}


  // this   job this user in his task in 
    public function acceptMyTask($id,$sphere_id,$project_id,$user_id,$member_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=DB::table('users')->where(['email'=>$userSession])->first();
            //when come inviation making a task , and accept on it ::: will become status_task_invitation for this task : accepted_status_task ,And will move into status preparing(category1) into status in-progress(category2) to start in it
            $updateTaskCount=  Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->count();
            if($updateTaskCount!==0){
              $updateTask=  Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->update(['status_task_invitation'=>'accepted_status_task','status_task'=>'in-progress_task','category_id'=>2]);
            }
            
            $taskCount= Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->count();
            if($taskCount!==0){
              $task= Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->first();
            }
            
            return view('tasks.welcome_into_your_task')->with(compact('user','task','sphere_id','project_id','user_id','member_id'));
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
    }
    public function disagreeMyTask($task_name=null,$sphereId=null,$project_id=null,$user_id=null,$member_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
            //when come inviation making a task , and disagree on it ::: will delete this task from db because i not accept on it 
            $taskCount= Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$task_name,'sphere_id'=>$sphereId,'project_id'=>$project_id])->count();
            if($taskCount!==0){
              Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$task_name,'sphere_id'=>$sphereId,'project_id'=>$project_id])->delete();
            }else{
              return redirect('/disagree-my-task/'.$task_name.'/'.$sphereId.'/'.$project_id.'/'.$user_id.'/'.$member_id)->with('flash_message_error','Data in this route is empty');
            }
            
            return redirect()->back()->with('flash_message_success','You Rejected this task');

      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
    }

    public function finishedMyTask($id,$sphere_id,$project_id,$user_id,$member_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
            //when i finished from a task will move this task from in-progress task status(category2) into finished status(category3)
            $updateTaskCount=  Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->count();
            if($updateTaskCount!==0){
              $updateTask=  Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->update(['status_task'=>'finished_task','category_id'=>3]);
            }else{
            return redirect('/finished-my-task/'.$id.'/'.$sphere_id.'/'.$project_id.'/'.$user_id.'/'.$member_id)->with('flash_message_error','The task that in route not exist');
            }
            $taskCount= Task::where(['user_id'=>$user_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->count();
            if($taskCount!==0){
              Task::where(['user_id'=>$user_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->first();
            }else{
            return redirect('/finished-my-task/'.$id.'/'.$sphere_id.'/'.$project_id.'/'.$user_id.'/'.$member_id)->with('flash_message_error','The task that in route not exist');
            }
            return redirect()->back()->with('flash_message_success','thank you on your finished task , wait the leader accept on it ');

      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
    }
    public function cancelMyTaskThatFinished($id_task){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=DB::table('users')->where(['email'=>$userSession])->first();

            //when i need move my task that i finished from it : from status finished(category3) into in-progress status(category2)
            $task=Task::where(['id'=>$id_task])->first();
            if(!empty($task)){
              Task::where(['id'=>$id_task])->update(['status_task'=>'in-progress_task','category_id'=>2]);
              return redirect()->back()->with('flash_message_success','your task return into status in-progress');
            }else{
              return redirect('/cancel-my-task-that-finished/'.$id_task)->with('flash_message_error','Data in this route not exist');

            }
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 

    }

    
    public function ViewDetailsMyTaskFinished($id,$sphere_id,$project_id,$user_id=null,$member_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=DB::table('users')->where(['email'=>$userSession])->first();

            $taskCount= Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->count();
            if($taskCount!==0){
              $task= Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->first();
              return view('tasks.view_details_my_task_finished')->with(compact('user','task','id_task','project_id','sphere_id','user_id'));
            }else{
              return redirect('/view-details-my-task-finished/'.$id.'/'.$sphere_id.'/'.$project_id.'/'.$user_id.'/'.$member_id)->with('flash_message_error','Data in this route not exsit');
            }
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
    }

    public function ViewDetailsMyTaskInprogress($id,$sphere_id,$project_id,$user_id=null,$member_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=DB::table('users')->where(['email'=>$userSession])->first();

            $taskCount= Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->count();
            if($taskCount!==0){
              $task= Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->first();
            }
            return view('tasks.view_details_my_task_in_progress')->with(compact('user','task','id_task','project_id','sphere_id','user_id'));
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
    }
    public function ViewDetailsMyTaskCompleted($id,$sphere_id,$project_id,$user_id=null,$member_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=DB::table('users')->where(['email'=>$userSession])->first();

            $taskCount= Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->count();
            if($taskCount!==0){
              $task= Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->first();
            }
            return view('tasks.view_details_my_task_completed')->with(compact('user','task','id_task','project_id','sphere_id','user_id'));

      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
      
    }
    
    public function ViewDetailsMyTask($id,$sphere_id,$project_id,$user_id=null,$member_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
              $user=User::where(['email'=>Session::get('sessionUser')])->first();
            
            $taskCount= Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->count();
            if($taskCount!==0){
              $task= Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->first();
            }
            return view('tasks.view_details_task')->with(compact('task','id_task','project_id','sphere_id','user'));
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
    }

  //this  job the leader task in  task the user
    public function acceptTaskThatFinished($id,$sphere_id,$project_id,$user_id,$member_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
            //when the leader  accept on my task ::: when the leader need move my task that i finished from it : from status finished(category3) into completed status(category4)
            $taskCount=  Task::where(['member_id'=>$member_id,'user_id'=>$user_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->count();
            if($taskCount){
              $updateTask=  Task::where(['member_id'=>$member_id,'user_id'=>$user_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->update(['status_task'=>'completed_task','category_id'=>4]);
              $task= Task::where(['member_id'=>$member_id,'user_id'=>$user_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->first();
            }
            return redirect()->back()->with('flash_message_success','you accepted on this task that finished');
      
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
    }
    public function disagreeTaskThatFinished($id,$sphere_id,$project_id,$user_id,$member_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=DB::table('users')->where(['email'=>$userSession])->first();

            //when the leader not accept on my task ::: when the leader need move my task that i finished from it : from status finished(category3) into in-progress status(category2)
            $taskCount=  Task::where(['member_id'=>$member_id,'user_id'=>$user_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->count();
            if($taskCount!==0){
              $updateTask=  Task::where(['member_id'=>$member_id,'user_id'=>$user_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->update(['status_task'=>'in-progress_task','category_id'=>2]);
              $task= Task::where(['member_id'=>$member_id,'user_id'=>$user_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->first();
            }
            return redirect()->back()->with('flash_message_success','you disagree on this task that finished');

      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
    }

    public function ViewDetailsTaskFinished($id,$sphere_id,$project_id,$user_id=null,$member_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=DB::table('users')->where(['email'=>$userSession])->first();
            $taskCount= Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->count();
            if($taskCount!==0){
              $task= Task::where(['user_id'=>$user_id,'member_id'=>$member_id,'id'=>$id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->first();
            }
            return view('tasks.view_details_task_that_finished')->with(compact('user','task','id_task','project_id','sphere_id','user_id','member_id'));

      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
    }
    public function ViewDetailsTaskInprogress($id,$sphere_id,$project_id,$user_id=null,$member_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=DB::table('users')->where(['email'=>$userSession])->first();

            $taskCount= Task::where(['user_id'=>$user_id,'id'=>$id,'member_id'=>$member_id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->count();
            if($taskCount!==0){
              $task= Task::where(['user_id'=>$user_id,'id'=>$id,'member_id'=>$member_id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->first();
            }
            return view('tasks.view_details_task_that_in_progress')->with(compact('user','task','id_task','project_id','sphere_id','user_id'));

      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
    }
    public function ViewDetailsTaskCompleted($id,$sphere_id,$project_id,$user_id=null,$member_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=DB::table('users')->where(['email'=>$userSession])->first();

            $taskCount= Task::where(['user_id'=>$user_id,'id'=>$id,'member_id'=>$member_id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->count();
            if($taskCount!==0){
              $task= Task::where(['user_id'=>$user_id,'id'=>$id,'member_id'=>$member_id,'sphere_id'=>$sphere_id,'project_id'=>$project_id])->first();
            }
            return view('tasks.view_details_task_that_completed')->with(compact('user','task','id_task','project_id','sphere_id','user_id'));

      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
    }

    public function viewDetailsTask($task_id=null,$sphere_id=null,$project_id=null,$user_id=null){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=DB::table('users')->where(['email'=>$userSession])->first();

            $taskCount=  Task::Where(['id'=>$task_id,'project_id'=>$project_id,'sphere_id'=>$sphere_id])->count();
            if($taskCount!==0){
              $task=  Task::Where(['id'=>$task_id,'project_id'=>$project_id,'sphere_id'=>$sphere_id])->first();
            }
            
            return view('tasks.view_details_task')->with(compact('task','user'));
      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
    }

    public function viewDetailsYourTask($task_id=null,$sphere_id=null,$project_id=null,$user_id=null){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=DB::table('users')->where(['email'=>$userSession])->first();

            $taskCount=  Task::Where(['id'=>$task_id,'project_id'=>$project_id,'sphere_id'=>$sphere_id])->count();
            if($taskCount!==0){
             $task=  Task::Where(['id'=>$task_id,'project_id'=>$project_id,'sphere_id'=>$sphere_id])->first(); 
            }
            return view('tasks.view_details_your_task')->with(compact('task','user'));

      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
      
    }
    public function getPageTasksSphere($sphere_id){
      $userSession=Session::get('sessionUser');
      if($userSession){

              $user=User::where(['email'=>Session::get('sessionUser')])->first();
              $user_id=$user->id;
            
            $sphereId=$sphere_id;
            $sphereCount=Sphere::where(['id'=>$sphere_id])->count();
            if($sphereCount!==0){
             $sphere=Sphere::where(['id'=>$sphere_id])->first(); 
            }
            $mySpheresFoundedCount=Sphere::where(['founder_id'=>$user_id])->count();
            if($mySpheresFoundedCount!==0){
              $mySpheresFounded=Sphere::where(['founder_id'=>$user_id])->get();
            }            

            $mySpheresJoinedCount=DB::table('sphere_users')->where(['user_id'=>$user_id])->count();
            if($mySpheresJoinedCount){
              $mySpheresJoined=DB::table('sphere_users')->where(['user_id'=>$user_id])->get();
            }
            
            $tasks_sphere_open_count=Task::where(['sphere_id'=>$sphere_id,'status_task'=>'in-progress_task'])->count();
            if($tasks_sphere_open_count){
              $tasks_sphere_open=Task::where(['sphere_id'=>$sphere_id,'status_task'=>'in-progress_task'])->get();
            }
            $tasks_sphere_close_count=Task::where(['sphere_id'=>$sphere_id,'status_task'=>'completed_task'])->count();
            if($tasks_sphere_close_count!==0){
              $tasks_sphere_close=Task::where(['sphere_id'=>$sphere_id,'status_task'=>'completed_task'])->get();
            }
            return view('tasks.get_tasks_sphere')->with(compact('mySpheresFounded','mySpheresJoined','sphere','tasks_sphere_open','tasks_sphere_open_count','my_tasks_open_count','tasks_sphere_close','tasks_sphere_close_count'));

      }else{
      return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
      } 
    }

    public function myTasks(){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $user_id=$user->id;
        return view('tasks.my_tasks')->with(compact('user_id'));
      }else{
        return redirect('/user/login')->with('flash_message_error','You have not authorization to access into this page');
        } 
    }
    public function getMyTasksCreatedByMe(){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $user_id=$user->id;
        
        $my_tasks_created_by_me=Task::where(['user_id'=>$user_id])->get();
       
        return response()->json([
          'status'=>200,
          'data'=>$my_tasks_created_by_me
        ]);
    }else{
      return response()->json([
        'status'=>401,
        'data'=>'You have not authorization to access into this page'
      ]);
    }
    }

    public function getMyTasksOpen(){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $user_id=$user->id;

        $my_tasks_open=Task::where(['member_id'=>$user_id,'status_task'=>'in-progress_task'])->get();
        return response()->json([
          'status'=>200,
          'data'=>$my_tasks_open
        ]);
    }else{
      return response()->json([
        'status'=>401,
        'data'=>'You have not authorization to access into this page'
      ]);
    } 
    }

    public function mySpheresFounded(){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $user_id=$user->id;
         $mySpheresFounded=Sphere::where(['founder_id'=>$user_id])->get(); 
        
        return response()->json([
          'status'=>200,
          'data'=>$mySpheresFounded
        ]);
    }else{
      return response()->json([
        'status'=>401,
        'data'=>'You have not authorization to access into this page'
      ]);
    }
    }

    public function mySpheresJoined(){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $user_id=$user->id;

         $mySpheresJoined=User::find($user_id)->spheresJoined()->get(); 

        return response()->json([
          'status'=>200,
          'data'=>$mySpheresJoined
        ]);
    }else{
      return response()->json([
        'status'=>401,
        'data'=>'You have not authorization to access into this page'
      ]);
    }
    }
    public function getMyTasksClose(){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $user_id=$user->id;

        $my_tasks_close=Task::where(['member_id'=>$user_id,'status_task'=>'completed_task'])->get();
        return response()->json([
          'status'=>200,
          'data'=>$my_tasks_close
        ]);
    }else{
      return response()->json([
        'status'=>401,
        'data'=>'You have not authorization to access into this page'
      ]);
    } 
    }

    public function getMyTasksAssignForMe(){
      $userSession=Session::get('sessionUser');
      if($userSession){
        $user=User::where(['email'=>Session::get('sessionUser')])->first();
        $user_id=$user->id;
        $my_tasks_assigned_for_me=Task::where(['member_id'=>$user_id])->get();
        return response()->json([
          'status'=>200,
          'data'=>$my_tasks_assigned_for_me
        ]);
    }else{
      return response()->json([
        'status'=>401,
        'data'=>'You have not authorization to access into this page'
      ]);
    }
    }


    public function addDescriptionTask(Request $req,$task_id,$user_id){
       $userSession=Session::get('sessionUser');
       if($userSession){
            $taskCount=Task::where(['id'=>$task_id])->count();
            if($taskCount!==0){
              $task=Task::where(['id'=>$task_id])->first();
              $task->description_task=$req->input('body');
              $task->user_id=$user_id;
              $task->save();
              return response()->json([
                'data'=>'save successfully',
                'status'=>200,
                'data'=>$task
              ]);
            }else{
              return response()->json([
                'status'=>404,
                'data'=>'this task'.$task_id.'not exist'
              ]);
            }
            

       }else{
         return response()->json([
           'status'=>401,
           'data'=>'You have not authorization to access into this page'
         ]);
       } 
    }
    public function addDidlineTask(Request $req,$task_id,$user_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
           $taskCount=Task::where(['id'=>$task_id])->count();
           if($taskCount!==0){
             $task=Task::where(['id'=>$task_id])->first();
             $task->end_task=$req->input('body');
             $task->user_id=$user_id;
             $task->save();
             return response()->json([
               'status'=>'save successfully',
               'status'=>200,
               'data'=>$task
             ]);
           }else{
             return response()->json([
               'status'=>404,
               'data'=>'this task'.$task_id.'not exist'
             ]);
           }
           

      }else{
        return response()->json([
          'status'=>401,
          'data'=>'You have not authorization to access into this page'
        ]);
      } 
   }
    public function getDescriptionTask($task_id){
       $userSession=Session::get('sessionUser');
       if($userSession){
             $taskCount=Task::where(['id'=>$task_id])->count();
             if($taskCount!==0){
              $task=Task::where(['id'=>$task_id])->first();
              return response()->json([
                'status'=>'save successfully',
                'status'=>200,
                'data'=>$task
              ]);
             }else{
               return response()->json([
                 'status'=>404,
                 'data'=>'this task'.$task_id.'not exist'
               ]);
             }

       }else{
         return response()->json([
           'status'=>401,
           'data'=>'You have not authorization to access into this page'
         ]);
       } 
    }
    public function getDidlineTask($task_id){
      $userSession=Session::get('sessionUser');
      if($userSession){
            $taskCount=Task::where(['id'=>$task_id])->count();
            if($taskCount!==0){
             $task=Task::where(['id'=>$task_id])->first();
             return response()->json([
               'status'=>'save successfully',
               'status'=>200,
               'data'=>$task
             ]);
            }else{
              return response()->json([
                'status'=>404,
                'data'=>'this task'.$task_id.'not exist'
              ]);
            }

      }else{
        return response()->json([
          'status'=>401,
          'data'=>'You have not authorization to access into this page'
        ]);
      } 
   }
    
    public function addCommentTask(Request $request,$categoryId,$sphereId=null,$taskId=null,$projectId,$userId)
    {
       $userSession=Session::get('sessionUser');
       if($userSession){
            $comment=new Comment;
            $comment->conversation_id=0;
            $comment->sphere_id=$sphereId;
            $comment->category_id=$categoryId;
            $comment->project_id=$projectId;
            $comment->user_id=$userId;
            $comment->task_id=$taskId;
            $comment->body=$request->input('body');
            $comment->save();
            return response()->json([
                'status'=>'save successfully',
                'status'=>200,
                'data'=>$comment
            ]);
       }else{
         return response()->json([
           'status'=>401,
           'data'=>'You have not authorization to access into this page'
       ]);
       } 
    }
    public function updateCommentTask(Request $request,$category_id,$commentId=null,$sphereId=null,$taskId=null,$projectId,$userId)
    {
       $userSession=Session::get('sessionUser');
       if($userSession){
            if($request->isMethod('put')){
              $commentCount=Comment::where(['id'=>$commentId])->count();
              if($commentCount!==0){
                $comment=Comment::findOrFail($commentId);
                $comment->category_id=$category_id;
                $comment->conversation_id=0;
                $comment->sphere_id=$sphereId;
                $comment->task_id=$taskId;
                $comment->user_id=$userId;
                $comment->id=$commentId;
                $comment->project_id=$projectId;
                $comment->body=$request->input('body');
                $comment->save();
                return response()->json([
                'status'=>'save successfully',
                'status'=>200,
                'data'=>$comment
                ]);
              }else{
                return response()->json([
                  'status'=>'this comment'.$commentId,'not exist',
                  'status'=>404
              ]);
              }
             
            }
       }else{
         return response()->json([
           'status'=>401,
           'data'=>'You have not authorization to access into this page'
       ]);
       } 
    }
    public function deleteCommentTask($category_id,$commentId=null,$sphereId=null,$taskId=null,$projectId,$userId)
    {
       $userSession=Session::get('sessionUser');
       if($userSession){
             $commentCount=Comment::where(['id'=>$commentId,'category_id'=>$category_id,'sphere_id'=>$sphereId,'task_id'=>$taskId,'project_id'=>$projectId,'user_id'=>$userId])->count();
             if($commentCount!==0){
               $comment=Comment::where(['id'=>$commentId,'category_id'=>$category_id,'sphere_id'=>$sphereId,'task_id'=>$taskId,'project_id'=>$projectId,'user_id'=>$userId])->delete();
               return response()->json([
                  'status'=>'delete successfully',
                  'status'=>200,
                  'data'=>$comment
              ]);
             }else{
               return response()->json([
                 'status'=>'this comment not exist',
                 'status'=>404
             ]);
             }

       }else{
         return response()->json([
           'status'=>401,
           'data'=>'You have not authorization to access into this page'
       ]);
       } 
    }

    public function updateTaskInCategory($task_id,$category_id){
      $task=Task::where(['id'=>$task_id])->first();
       $task->category_id=$category_id;
       $task->status_task='completed_task';
       $task->save();
       if($task->save()){
        return response()->json([
        'status'=>200,
        'data'=>$task
    ]); 
       }
      
    }
}
