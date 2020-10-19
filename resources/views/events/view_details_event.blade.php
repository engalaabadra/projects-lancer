@extends ("layout")
@section("content")
<?php
use App\Event;
$usersSphereCount=DB::table('sphere_users')->where(['sphere_id'=>$sphere_id,'invitation_status'=>'accepted_inivitation_status',['user_id','!=',$userId]])->orWhere(['request_joining_status'=>'accepted_status_request_joining',['user_id','!=',$userId]])->count();//invit member into the event , which is these members must be in this sphere and not send into his invitation before time
if($usersSphereCount!==0){
  $usersSphere=DB::table('sphere_users')->where(['sphere_id'=>$sphere_id,'invitation_status'=>'accepted_inivitation_status',['user_id','!=',$userId]])->orWhere(['request_joining_status'=>'accepted_status_request_joining',['user_id','!=',$userId]])->get();//invit member into the event , which is these members must be in this sphere and not send into his invitation before time
}
$sphereCount=DB::table('spheres')->where(['id'=>$sphere_id])->count();
if($sphereCount!==0){
  $sphere=DB::table('spheres')->where(['id'=>$sphere_id])->first();
  $sphereName=$sphere->name;
}
?>
@if($eventCount!==0)
<?php
  $eventCount=Event::where(['id'=>$event->id])->count();
  if($eventCount!==0){
   $event=Event::where(['id'=>$event->id])->first(); 
  }
  ?>
<div class="bodysec">
  <div class="container-fluid">
      <div class="row">
          
          <div class="leftpanel">
              <div class="top">
                  <img src="images/img.png" alt="" />
                  @if($sphereCount!==0)
                    <h1>Name of Sphere :
                    <a href="{{url('/sphere/'.$sphere_id.'/posts')}}">{{$sphere->name}}</a>
                    <br /><span>Events</span></h1>
                  @endif
              </div>
              <div class="details">
                @if($eventCount!==0)
                  <h3>Title Of Event</h3>
                  <p>{{$event->title}}</p>
                  <h4>Description</h4>
                  <h5>{{$event->description}} </h5>
                  <h6>Start time : {{$event->event_time}}  <br /></h6>
                @endif
              </div>
              
          </div>
          <div class="rightpanel">
              <div class="video-box">
                  <img src="{{asset('images/uploads/video.png')}}" alt="">
              </div>
              <div class="chat-box">
                  <div class="edit"><img src="{{asset('images/uploads/edit.png')}}" alt=""></div>
                  <div class="chat-back">
                      <img src="{{asset('images/uploads/message-box.png')}}" alt="">
                  </div>
                  
              </div>
          </div>
      </div>
  </div>
  <h3>invit members into this event</h3>
  
  {{-- get all members this sphere , must be these members accepted status invitation or joining  --}}
  @if($usersSphereCount!==0) 
    @foreach($usersSphere as $user)
      <?php
      $memberSphere= DB::table('users')->where(['id'=>$user->user_id])->first();
      $memberSphereCount= DB::table('users')->where(['id'=>$user->user_id])->count();
      ?>
      @if($memberSphereCount!==0)
        {{-- are these members is exsit in this event or not --}}
        <?php
        $eventUserCount=DB::table('events_users')->where(['user_id'=>$user->user_id,'event_id'=>$event->id,'sphere_id'=>$sphere_id])->count();
        ?>
        @if($eventUserCount==0)
          {{-- if not exist , will show link for invit member  --}}
          <h6>{{$memberSphere->email}}</h6>
          <a href="{{url('/invit-member/'.$memberSphere->id.'/sphere/'.$sphere_id.'/event/'.$event->id)}}">invit member</a>
        @else
          {{-- if exist , but is pending , will show link -> sent the invitation , wait his accepting it  --}}
          <?php
          $eventPendingUserCount=DB::table('events_users')->where(['user_id'=>$memberSphere->id,'event_id'=>$event->id,'sphere_id'=>$sphere_id,'invitation_status'=>'status_pending'])->count();
          ?>
          @if($eventPendingUserCount!==0)
            <h6>{{$memberSphere->email}}</h6>
            <h5 style="color:red">sent the invitation , wait his accepting it</h5>
            <a href="{{url('/cancel-my-invitation-into-event/'.$memberSphere->id.'/'.$event->id.'/'.$sphere_id)}}">cancel</a>  
    
          @endif
        @endif
      @endif
    @endforeach
    
    <div class="alert alert-warning">
      you can invit persons to join in sphere <a href="{{url('/sphere/'.$sphere_id.'/posts')}}">from here</a> after that to join in this event  
    </div>  
  @else
    {{-- if this sphere not contain on any persons , so here not exist any person to join into this event , so show a msg : there is no members in this sphere until now , so you invitation persons to join in this sphere  --}}
    <div class="alert alert-info" role="alert">
      <h4>there is no members in this sphere until now , so you invitation persons to join in this sphere  <a href="{{url('/sphere/'.$sphere_id.'/posts')}}">from here</a></h4>
    </div>
  @endif
</div>

{{-- <div id="app"> --}}
  <chatroom userId="{{$userId}}" sphereId="{{$sphere_id}}" spherename="{{$sphereName}}"/>
{{-- </div> --}}


@endif

@endsection
