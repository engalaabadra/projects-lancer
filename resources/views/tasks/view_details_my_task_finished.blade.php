


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

</div>
@endif
<a href="{{url('/user/get-all-invitations/'.$user->id)}}">view all invitations</a>

@endsection