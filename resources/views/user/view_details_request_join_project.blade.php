


@extends ("layout")
@section("content")
@if($projectCount!==0)
<div class="card">
     <div class="card-header">
     </div>
     <div class="card-body">
         desc: {{$project->description}}
         image:     {{$project->image}}
         name:   {{$project->name}}
     </div>
     <?php
      ?>
      @if($requestJoinProjectCount!==0)
         <a href="{{url('/accept-on-request-join-project/'.$userId.'/'.$sphere_id.'/'.$project->id)}}" class="btn btn-success">Accept Request Joining</a>
         <a href="{{url('/decline-on-request-join-project/'.$userId.'/'.$sphere_id.'/'.$project->id)}}" class="btn btn-success">Decline Request Joining</a>
      @endif
</div>
@endif
<a href="{{url('/user/get-all-invitations/'.$userId)}}">view all invitations</a>

@endsection
