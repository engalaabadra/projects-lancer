


@extends ("layout")
@section("content")
<?php
use App\User;
use App\Task;

?>
@if($sphereCount!==0)
<div class="card">
     <div class="card-header">
         name:   {{$sphere->name}}
     </div>
     <div class="card-body">
         desc: {{$sphere->description}}
         image:   {{$sphere->image}}
     </div>
@endif
@if($inivitCount!==0) 
    <a href="{{url('/accept-inivitation/'.$user->id.'/'.$sphere->id)}}" class="btn btn-success">Accept Request Invitation</a>
    <a href="{{url('/decline-inivitation/'.$user->id.'/'.$sphere->id)}}" class="btn btn-success">Decline Request Invitation</a>
@endif
</div>
<a href="{{url('/user/get-all-invitations/'.$user->id)}}">view all invitations</a>
@endsection

