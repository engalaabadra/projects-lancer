@extends ("layout")
@section("content")
@if($eventCount!==0)
<div class="card">
     <div class="card-header">
        name:   {{$event->name}}
     </div>
     <div class="card-body">

        desc: {{$event->description}}
        image:     {{$event->image}}
     </div>
   @if($requestJoineventCount!==0)
      <a href="{{url('/accept-on-request-join-event/'.$userId.'/'.$sphere_id.'/'.$event->id)}}" class="btn btn-success">Accept Request Joining</a>
      <a href="{{url('/decline-on-request-join-event/'.$userId.'/'.$sphere_id.'/'.$event->id)}}" class="btn btn-success">Decline Request Joining</a>
   @endif
</div>
@endif
<a href="{{url('/user/get-all-invitations/'.$userId)}}">view all invitations</a>

@endsection
