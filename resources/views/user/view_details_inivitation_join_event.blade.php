


@extends ("layout")
@section("content")
@if($eventCount!==0)
<div class="card">
     <div class="card-header">
         name:   {{$event->title}}
     </div>
     <div class="card-body">
    desc: {{$event->description}}
     </div>
     <?php
$user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();

   $inivitCount=DB::table('events_users')->where(['user_id'=>$user->id,'invitation_status'=>'status_pending','event_id'=>$event->id,'sphere_id'=>$sphere_id])->count();?>
   @if($inivitCount!==0)
      <a href="{{url('/accept-inivitation-into-event/'.$user->id.'/event/'.$event->id.'/sphere/'.$sphere_id)}}" class="btn btn-success">Accept Inivitation</a>
      <a href="{{url('/decline-inivitation-into-event/'.$user->id.'/event/'.$event->id.'/sphere/'.$sphere_id)}}" class="btn btn-success">Decline Inivitation</a>
   @else
      <a href="{{url('/view-details-event/'.$sphere_id.'/'.$event->id)}}">view event {{$event->title}}</a>
   @endif
</div>
@endif
<a href="{{url('/user/get-all-invitations/'.$user->id)}}">view all invitations</a>

@endsection
