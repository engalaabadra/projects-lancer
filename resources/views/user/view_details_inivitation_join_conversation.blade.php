


@extends ("layout")
@section("content")
@if($conversationCount!==0)
<div class="card">
     <div class="card-header">
        name: {{$conversation->title}}
     </div>
     <div class="card-body">
    desc:{{$conversation->description}}
     </div>
     <?php
$user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();

   $inivitCount=DB::table('conversations_users')->where(['user_id'=>$user->id,'invitation_status'=>'status_pending','conversation_id'=>$conversation->id,'sphere_id'=>$sphere_id])->count();?>
  
  @if($inivitCount!==0){
      <a href="{{url('/accept-inivitation-into-conversation/'.$user->id.'/conversation/'.$conversation->id.'/sphere/'.$sphere_id)}}" class="btn btn-success">Accept Inivitation</a>
      <a href="{{url('/decline-inivitation-into-conversation/'.$user->id.'/conversation/'.$conversation->id.'/sphere/'.$sphere_id)}}" class="btn btn-success">Decline Inivitation</a>
   @else
    <a href="{{url('/view-conversation/'.$conversation->id.'/sphere/'.$sphere_id)}}">view conversation {{$conversation->title}}</a>
   @endif
</div>
@endif
<a href="{{url('/user/get-all-invitations/'.$user->id)}}">view all invitations</a>

@endsection
