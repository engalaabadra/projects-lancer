@extends ("layout")
@section("content")
@if($sphereCount!==0)
<div class="card">
     <div class="card-header">
     </div>
     <div class="card-body">
      desc: {{$sphere->description}}
      image:     {{$sphere->image}}
      name:   {{$sphere->name}}
     </div>
   @if($inivitCount!==0)
      <a href="{{url('/accept-on-request-join-sphere/'.$userId.'/'.$sphere->id)}}" class="btn btn-success">Accept Request Joining</a>
      <a href="{{url('/decline-on-request-join-sphere/'.$userId.'/'.$sphere->id)}}" class="btn btn-success">Decline Request Joining</a>
   @endif
</div>
@endif
<a href="{{url('/user/get-all-invitations/'.$userId)}}">view all invitations</a>

@endsection
