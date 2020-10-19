


@extends ("layout")
@section("content")
@if($projectCount!==0)
<div class="card">
     <div class="card-header">
        name:   {{$project->name}}
   </div>
     <div class="card-body">
         desc: {{$project->description}}
         image:     {{$project->image}}
     </div>
     <?php
      $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
      $inivitCount=DB::table('projects_users')->where(['user_id'=>$user->id,'invitation_status'=>'status_pending','project_id'=>$project->id,'sphere_id'=>$sphere_id])->count();?>
      @if($inivitCount!==0)    
         <a href="{{url('/accept-inivitation-into-project/'.$user->id.'/project/'.$project->id.'/sphere/'.$sphere_id)}}" class="btn btn-success">Accept Inivitation</a>
         <a href="{{url('/decline-inivitation-into-project/'.$user->id.'/project/'.$project->id.'/sphere/'.$sphere_id)}}" class="btn btn-success">Decline Inivitation</a>
      @endif
</div>

@endif
<a href="{{url('/user/get-all-invitations/'.$user->id)}}">view all invitations</a>

@endsection
