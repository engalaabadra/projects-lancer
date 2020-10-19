


@extends ("layout")
@section("content")
@if(!empty($task))
<div class="card">
  <div class="card-header">
    {{$task->name_task}}
 </div>
 <div class="card-body">
    desc: {{$task->description_task}}
    start:     {{$task->start_task}}
    end:   {{$task->end_task}}
    id project:   {{$task->project_id}}
    id sphere:   {{$task->sphere_id}}
 </div>
  <?php
    $taskCompletedCount=DB::table('tasks')->where(['name_task'=>$task->name_task,'status_task'=>'completed_task','user_id'=>$user_id,'project_id'=>$project_id,'sphere_id'=>$sphere_id])->count();?>
    @if($taskCompletedCount==!0)
        <div class="alert alert-info">
            you accepted on this task
        </div>
   @endif
</div>
@endif
<a href="{{url('/user/get-all-invitations/'.$user->id)}}">view all invitations</a>

@endsection
