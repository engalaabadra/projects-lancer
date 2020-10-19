


@extends ("layout")
@section("content")
@if($taskCount!==0)
<div class="card">
     <div class="card-header">
     </div>
     <div class="card-body">
         desc: {{$task->description}}
         image:     {{$task->image}}
         name:   {{$task->name}}
     </div>
     <?php
      $user=User::where(['email'=>Session::get('sessionUser')])->first();
      $requestJointaskCount=DB::table('tasks_users')->where(['user_id'=>$userId,'request_joining_status'=>'pending_status_request_joining','task_id'=>$task->id,'sphere_id'=>$sphere_id])->count();?>
      @if($requestJointaskCount!==0)
         <a href="{{url('/accept-on-request-join-task/'.$userId.'/'.$sphere_id.'/'.$task->id)}}" class="btn btn-success">Accept Request Joining</a>
         <a href="{{url('/decline-on-request-join-task/'.$userId.'/'.$sphere_id.'/'.$task->id)}}" class="btn btn-success">Decline Request Joining</a>
      @endif
</div>
@endif
<a href="{{url('/user/get-all-invitations/'.$userId)}}">view all invitations</a>

@endsection
