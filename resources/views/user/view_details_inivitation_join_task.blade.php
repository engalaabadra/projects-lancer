


@extends ("layout")
@section("content")
@if($taskCount!==0)
<div class="card">
     <div class="card-header">
        name:   {{$task->name}}
     </div>
     <div class="card-body">
         desc: {{$task->description}}
         image:     {{$task->image}}
     </div>
     <?php
      ?>
      @if($inivitCount!==0)    
         <a href="{{url('/accept-inivitation-into-task/'.$user->id.'/task/'.$task->id.'/sphere/'.$sphere_id.'/project/'.$project_id.'/category/'.$task->category_id)}}" class="btn btn-success">Accept Inivitation</a>
         <a href="{{url('/decline-inivitation-into-task/'.$user->id.'/task/'.$task->id.'/sphere/'.$sphere_id.'/project/'.$project_id.'/category/'.$task->category_id)}}" class="btn btn-success">Decline Inivitation</a>
      @endif
</div>

@endif
<a href="{{url('/user/get-all-invitations/'.$user->id)}}">view all invitations</a>

@endsection
