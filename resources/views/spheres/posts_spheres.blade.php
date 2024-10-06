
@extends ("layout")
@section("content")
<?php
use App\Sphere;
use App\Comment;
use App\Task;
use App\User;
?>
<a href="{{url('posts-sphere/'.$sphereId)}}">posts sphere</a>
<a href="{{url('/get-arena/'.$sphereId)}}">Arena sphere</a>
<div class="bodysec survey">
  <div class="survey-details-back">
      <div class="main_back">
          <div class="left_task">
              <ul class="left_pro">
                <?php
                  $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
                ?>
                  <li> <img src="{{asset('/images/backend_images/user/small/'.$user->image)}}" alt=""><a href="{{url('user/view-profile/'.$user->email)}}">Profile</a> </li>
                  <li> <img src="{{asset('images/uploads/settings.png')}}" alt=""><a href="{{url('/user/edit-profile/'.$user->id)}}">Settings</a></li>
               
               <?php 
                  $founderSphereCount=DB::table('spheres')->where(['founder_id'=>$user->id,'id'=>$sphereId])->count();
                  $JoinerSphereCountInvitation=DB::table('sphere_users')->where(['user_id'=>$user->id,'sphere_id'=>$sphereId,'is_lead'=>1,'invitation_status'=>'accepted_inivitation_status'])->count();
                  $JoinerSphereCountRequest =DB::table('sphere_users')->where(['user_id'=>$user->id,'sphere_id'=>$sphereId,'is_lead'=>1,'request_joining_status'=>'accepted_status_request_joining'])->count();
                ?>
                  @if($founderSphereCount!==0||$JoinerSphereCountInvitation!==0||$JoinerSphereCountRequest!==0)
                    <a href="{{url('/user/edit-cover-image-sphere/'.$sphereId)}}" class="btn btn-info">edit cover image sphere</a>
                    <a href="{{url('/user/edit-image-sphere/'.$sphereId)}}" class="btn btn-info">edit  image sphere</a>
                  @endif
              </ul>              

          </div>
          <div class="middle_section column-section survey">
            
          <div class="portion">
            <h3>My spheres</h3>
            @if($mySpheresCount==0)
              <div class="alert alert-info">
                there is no spheres you created  it until now
              </div>
            @else
            <ul class="spears_details">
              @foreach($mySpheres as $mySphere)
                <li>
                    <img src="{{asset('/images/backend_images/spheres/small/'.$mySphere->image)}}" alt="">
                    <p>{{$mySphere->name}}</p>
                </li>
                @endforeach
              </ul>
            @endif
          </div>
          <?php 
            $projectsSphereCount=DB::table('projects')->where(['sphere_id'=>$sphere_id])->count();
          ?>
              @if($projectsSphereCount!==0)
                @foreach($projectsSphere as $projectSphere)
                  <div class="portion">
                      <h3>Project: <a href="{{url('/view-details-project/'.$projectSphere->sphere_id.'/'.$projectSphere->id)}}">{{$projectSphere->name}}</a> </h3>
                      <?php 
                    $projectCompletedCount=DB::table('tasks')->where(['project_id'=>$projectSphere->id,'sphere_id'=>$projectSphere->sphere_id,'status_task'=>'completed'])->count();
                    $projectInProgressCount=DB::table('tasks')->where(['project_id'=>$projectSphere->id,'sphere_id'=>$projectSphere->sphere_id,'status_task'=>'in-progress'])->count();
                  
                  ?>
                  @if($projectCompletedCount!==0)
                  <?php 

                    DB::table('projects')->where(['id'=>$projectSphere->id])->update(['status_project'=>'completed']);
                  ?>
                  @endif
                  @if($projectInProgressCount!==0)
                  <?php 

                    DB::table('projects')->where(['id'=>$projectSphere->id])->update(['status_project'=>'in-progress']);
                  ?>
                  @endif
                      @if($projectSphere->status_project=='completed')
                        <h5 style="color:green">{{$projectSphere->status_project}}</h5>
                      @elseif($projectSphere->status_project=='in-progress')
                        <h5 style="color:red">{{$projectSphere->status_project}}</h5>
                      @endif
                      <h4>{{$projectSphere->description}}</h4>
                  </div>
                @endforeach
              @endif
              <?php
                $tasksSphereCount=DB::table('tasks')->where(['sphere_id'=>$sphere_id])->count();

              ?>
              @if($tasksSphereCount!==0)
                @foreach($tasksSphere as $taskSphere)
                  <div class="portion">
                      Task Name:<h3>{{$taskSphere->name_task}}</h3>
                        @if($taskSphere->status_task=="completed")
                        <h5 style="color: blue">{{$taskSphere->status_task}}</h5>
                        @elseif($taskSphere->status_task=="in-progress")
                        <h5 style="color: red">{{$taskSphere->status_task}}</h5>
                        @endif
                      <h4>Task Description:{{$taskSphere->description_task}}</h4>
                      <p>From:{{$taskSphere->start_task}}</p>
                      <p>To:{{$taskSphere->end_task}}</p>
                  </div>
                @endforeach
              @endif
                <?php 
                  $surveysSphereCount=DB::table('surveys')->where(['sphere_id'=>$sphere_id])->count();
                  ?>
              @if($surveysSphereCount!==0)
              @foreach($surveysSphere as $surveySphere)
                <div class="portion">
                    Survey Name:<h3>{{$surveySphere->name}}</h3>
                    <h4>Survey Description:{{$surveySphere->description}}</h4>
                </div>
              @endforeach
            @endif
              <?php 
                $eventsSphereCount=DB::table('events')->where(['sphere_id'=>$sphere_id])->count();

              ?>
            @if($eventsSphereCount!==0)
              @foreach($eventsSphere as $eventSphere)
                <div class="portion">
                    Event Name:<h3>{{$eventSphere->name}}</h3>
                    <h4>Event Description:{{$eventSphere->description}}</h4>
                    <p>event time:{{$eventSphere->event_time}}</p>

                </div>
              @endforeach
            @endif
            <?php 
             $conversationsSphereCount=DB::table('conversations')->where(['sphere_id'=>$sphere_id])->count();

            ?>
            @if($conversationsSphereCount!==0)
              @foreach($conversationsSphere as $conversationSphere)
                <div class="portion">
                    Conversation Name:<h3><a href="{{url('/view-conversation/'.$conversationSphere->id.'/sphere/'.$conversationSphere->sphere_id)}}">{{$conversationSphere->name}}</a></h3>
                    <h4>Conversation Description:{{$conversationSphere->description}}</h4>
                </div>
              @endforeach
            @endif
              

          </div>
          <span>invit members into this sphere</span>
{{-- search about followers this user --}}
<?php
$user=User::where(['email'=>Session::get('sessionUser')])->first();
$userId=$user->id;
$myFollowers=DB::table('users_followers')->where(['user_id'=>$user->id,'status_following'=>'accepted_status',['follower_id','!=',$user->id]])->get();
$myFollowersCount=DB::table('users_followers')->where(['user_id'=>$user->id,'status_following'=>'accepted_status',['follower_id','!=',$user->id]])->count();
?>
{{-- if exist followers for this user  --}}
@if($myFollowersCount!==0)
  @foreach($myFollowers as $myFollower)  
    <?php 
    $myFollowerExsitInSphereCount=DB::table('sphere_users')->where(['user_id'=>$myFollower->follower_id,'sphere_id'=>$sphere_id])->count();
    ?>
    <?php  $follower=User::where(['id'=>$myFollower->follower_id])->first() ?>
    {{--  if the follower is not exist in this sphere: --}}
    @if($myFollowerExsitInSphereCount==0)
    {{-- i will show him to invit him --}}
    <h5>{{$follower->email}}</h5>
      <a href="{{url('/invit-member/'.$myFollower->follower_id.'/sphere/'.$sphere_id)}}">invit member</a>
    @else
      <?php  
          $myFollowerExsitPendingInSphereCount=DB::table('sphere_users')->where(['user_id'=>$myFollower->follower_id,'sphere_id'=>$sphere_id,'invitation_status'=>'pending_inivitation_status'])->count();
      ?>
      {{--  if the follower is  exist in this sphere , but he pending invitiation , not accepted: --}}
      {{--  i will show this :sent the invitation , wait his accepting  --}}
      @if($myFollowerExsitPendingInSphereCount!==0)
        <h5>{{$follower->email}}</h5>
        <h5 style="color:red">sent the invitation , wait his accepting  , you can make 
        <a href="{{url('/cancel-my-invitation-into-sphere/'.$follower->id.'/'.$sphere_id)}}">cancel</a>  
      @else
          <h5>you can invit another members from 
            <a href="{{url('/show-all-users/'.$sphere_id)}}">here</a>
          </h5>

      @endif
    @endif
  @endforeach

@else
  <h4>you has not followers so you can invit persons from 
    <a href="{{url('/show-all-users/'.$sphere_id)}}">here</a>
  </h4>
  
@endif
          <div class="right_task">
            <div class="oval_navigation">
              <a href="{{url('/sphere/'.$sphere->id.'/surveys')}}" class="round1">
                <span>Surveys</span>
              </a>
              <a href="{{url('/get-arena/'.$sphere->id)}}" class="round2">
                <span>Arena</span>
              </a>
              <a href="{{url('/sphere/'.$sphere->id.'/tasks')}}" class="round3">
                <span>Tasks</span>
              </a>
              <a href="{{url('/sphere/'.$sphere->id.'/projects')}}" class="round4">
                <span>Projects</span>
              </a>

              <a href="{{url('/sphere/'.$sphere->id.'/conversations')}}" class="round5">
                <div>
                    <img src="{{asset('/images/backend_images/spheres/small/'.$sphere->image)}}" alt="" style="   border-radius: 50%;    border-radius: 50%;
                    margin-top: 63px;">
                  <br />
                  <span>The Sphere</span><br />Conversations
                </div>
              </a>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection
