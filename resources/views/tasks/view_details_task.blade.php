@extends ("layout")
@section("content")
<?php
use App\User;
use App\Task;

?>
     @if(!empty($task))
<div class="card">
     <div class="card-header">
        {{$task->id}}
     </div>
     <div class="card-body">
        desc: {{$task->description_task}}
        start:     {{$task->start_task}}
        end:   {{$task->end_task}}
        id project:   {{$task->project_id}}
        id sphere:   {{$task->sphere_id}}
     </div>
     <?php
     $taskId=$task->id;
     $userCountStatusMsg=User::where(['email'=>Session::get('sessionUser'),'status_message'=>'sent message'])->count();
     $userStatusMsg=User::where(['email'=>Session::get('sessionUser'),'status_message'=>'sent message'])->first();
     $user = User::where(['email'=>Session::get('sessionUser')])->first();
     $taskCount=Task::where(['id'=>$task->id,'status_task_invitation'=>'pending_status_task','member_id'=>$user->id,'project_id'=>$project_id,'sphere_id'=>$sphere_id])->count();?>
    @if($taskCount!==0)
    
        <a href="{{url('/accept-my-task/'.$task->id.'/'.$task->sphere_id.'/'.$task->project_id.'/'.$task->user_id.'/'.$user->id)}}" class="btn btn-success">Accept Task</a>
        <a href="{{url('/disagree-my-task/'.$task->id.'/'.$task->sphere_id.'/'.$task->project_id.'/'.$task->user_id.'/'.$user->id)}}" class="btn btn-success">DisAgree Task</a> 
     @else
        <?php
        $user = User::where(['email'=>Session::get('sessionUser')])->first();
        $taskCount=Task::where(['id'=>$task->id,'status_task'=>'in-progress_task','member_id'=>$user->id,'project_id'=>$project_id,'sphere_id'=>$sphere_id])->count();?>
        @if($taskCount!==0)
            <h5 style="color: red;">in-progress task</h5>
            <a href="{{url('/finished-my-task/'.$task->id.'/'.$task->sphere_id.'/'.$task->project_id.'/'.$task->user_id.'/'.$user->id)}}" class="btn btn-success">Finished Task</a>
        @endif
        <?php
        $user = User::where(['email'=>Session::get('sessionUser')])->first();
        $taskInProgressCount=Task::where(['id'=>$task->id,'status_task'=>'in-progress_task','user_id'=>$task->user_id,'member_id'=>$user->id,'project_id'=>$project_id,'sphere_id'=>$sphere_id])->count();
        $taskCompletedCount=Task::where(['id'=>$task->id,'status_task'=>'completed_task','user_id'=>$task->user_id,'member_id'=>$user->id,'project_id'=>$project_id,'sphere_id'=>$sphere_id])->count();
        $taskFinishedCount=Task::where(['id'=>$task->id,'status_task'=>'finished_task','user_id'=>$task->user_id,'member_id'=>$user->id,'project_id'=>$project_id,'sphere_id'=>$sphere_id])->count();?>
        @if($taskFinishedCount!==0)
            <div class="alert alert-warning">
                wait accept leader on your task that finished
            </div>
            and you can <a href="{{url('cancel-my-task-that-finished/'.$task->id)}}">Undo the process  </a> 
        @elseif($taskCompletedCount!==0)
            <div class="alert alert-info">
               Your task that finished accepted from the leader 
          </div> 
        @elseif($taskInProgressCount!==0)
          <div class="alert alert-info">
               Your task that still in-progress
          </div> 
        @endif
    @endif
</div>
@endif
<a href="{{url('/user/get-all-invitations/'.$user->id)}}">view all invitations</a>

@endsection