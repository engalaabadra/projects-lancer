@extends ("layout")
@section("content")
<br  class="col-sm-12 mt-5"/>
<div >
  <div   style="     width: 47%;
            margin-left: 625px;">
            userId : {{$userId}}
    <postshome user_id="{{$userId}}"/> 
  </div>

  <div class="middle_section feed3">
    <div class="newsfeedmidpart" style="    margin-top: 502px;">
      <div class="midbox">

        @if($spheresThisUserJoinedInItCount!==0)
        <h2 style="margin-left: 654px;">spheres This User Joined In It</h2> 
        @foreach($spheresThisUserJoinedInIt as $sphereThisUserJoinedInIt)
          <?php 
          $sphere=DB::table('spheres')->where(['id'=>$sphereThisUserJoinedInIt->sphere_id])->first();
          ?> 
              <div class="sglefeedsec bg01" style="width: 47%;!important;
              margin-left: 636px;!important;">
                <div class="lftimgsec"><img src="{{asset('/images/backend_images/spheres/small/'.$sphere->image)}}" alt="" /></div>
                <div class="rytimgcontsec">
                  <h2>
                    <a href="{{url('sphere/'.$sphere->id.'/posts')}}">{{$sphere->name}}</a>
                    </h2>
                  Established:  {{$sphere->created_at}}<br>
                  Primary Focus:  {{$sphere->primary_focus}}<br>
                  <a href="{{url('/sphere/'.$sphere->id.'/tasks')}}">Tasks</a>  
                  <a href="{{url('/sphere/'.$sphere->id.'/surveys')}}">Surveys</a>  
                  <a href="{{url('/sphere/'.$sphere->id.'/projects')}}">Projects</a>  
                    <?php 
                   $arenaSphereCount=   DB::table('arenas')->where(['sphere_id'=>$sphere->id])->first();

?>
                  @if($arenaSphereCount!==0)
                  <a href="{{url('/get-arena/'.$sphere->id)}}">Arena</a>  
                  @endif
                  </p>
              </div>
              </div>
        @endforeach
        @endif

        @if($projectsThisUserJoinedInItCount!==0)
          <h2 style="margin-left: 654px;"> projects This User Joined In It</h2>  
          @foreach($projectsThisUserJoinedInIt as $projectThisUserJoinedInIt)
            <?php 
            $project=DB::table('projects')->where(['id'=>$projectThisUserJoinedInIt->project_id])->first();
            $sphere=DB::table('spheres')->where(['id'=>$projectThisUserJoinedInIt->sphere_id])->first();
            ?>
            @if(!empty($project))
              <div class="sglefeedsec bg01" style="width: 47%;!important;
              margin-left: 636px;!important;">
                <div class="lftimgsec"><img src="{{asset('/images/backend_images/projects/small/'.$project->image)}}" alt="" /></div>
                  <h4><a href="{{url('sphere/'.$sphere->id.'/posts')}}">{{$sphere->name}}</a></h4> 
                </div>
              </div>
            @endif
          @endforeach
        @endif


        @if($eventsThisUserJoinedInItCount!==0)
          <h2 style="margin-left: 654px;"> events This User Joined In It</h2>  
          @foreach($eventsThisUserJoinedInIt as $eventThisUserJoinedInIt)
            <?php 
              $event=DB::table('events')->where(['id'=>$eventThisUserJoinedInIt->event_id])->first();
              $eventCount=DB::table('events')->where(['id'=>$eventThisUserJoinedInIt->event_id])->count();
            ?>
            @if($eventCount!==0)
            <div class="sglefeedsec sglenewsfeedsec"style="width: 47%;!important;
            margin-left: 636px;!important;">
              <div class="rytshape"><span>A</span></div>
              <?php 
                $user=DB::table('users')->where(['id'=>$event->user_id])->first();
              ?>
              <div class="lftimgsec"><a href="{{url('view-details-event/'.$eventThisUserJoinedInIt->sphere_id.'/'.$event->id)}}"><img src="{{asset('/images/backend_images/user/small/'.$user->image)}}" alt="" /></a></div>
              <a href="{{url('view-details-event/'.$eventThisUserJoinedInIt->sphere_id.'/'.$event->id)}}">{{$event->title}}</a>
              <div class="rytimgcontsec">
                
                <p>{{$event->description}}</p>
                <p>{{$event->event_time}}</p>
            </div>
            @endif
          @endforeach
        @endif

        

        @if($spheresThisUserJoinedInItCount!==0)
        <h2 style="margin-left: 654px;">tasks from spheres Jointin it</h2>              
          @foreach($spheresThisUserJoinedInIt as $sphereThisUserJoinedInIt)
          <?php
            $tasksSpheresCount=  DB::table('tasks')->where(['sphere_id'=>$sphereThisUserJoinedInIt->sphere_id])->count();
            $tasksSpheres=  DB::table('tasks')->where(['sphere_id'=>$sphereThisUserJoinedInIt->sphere_id])->get();
          ?>
          @if($tasksSpheresCount!==0)
            @foreach($tasksSpheres as $tasksphere)
            <div class="sglefeedsec bg01" style="width: 47%;!important;
            margin-left: 636px;!important;">
              <div class="rytimgcontsec">
                <p>
                  Name Task:  {{$tasksphere->name_task}}<br>
                  Description  {{$tasksphere->description_task}}<br>
                  status project :  {{$tasksphere->status_task}}<br>
                  From :  {{$tasksphere->start_task}}<br>
                  To :  {{$tasksphere->end_task}}<br>
                  </p>
            </div>
          @endforeach
        @endif
        @endforeach
        @endif
        @if(!empty($sphereForAnswerUser1Count))
        @if($sphereForAnswerUser1Count!==0)
        {{-- {{dd($subSpheresCount1)}} --}}
        @if($subSpheresCount1!==0)
        <h4>Suggestion spheres</h4> 
          @if($subSpheres1Count==0)
          <div class="alert alert-info">
              there is no Suggestion spheres , just wait activate spheres  from admin ,that will be Suggestion for you
          </div>
          @else
            @foreach($subSpheres1 as $subSphere)
            <?php
              $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
              $userId=$user->id;
              $sphereUserCount=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$subSphere->id])->count();?>
              {{-- check this user exist  in this sphere --}}
              @if($sphereUserCount==0)
              {{-- if not exist , will show this word -> join , to allow to press on it , to join into this sphere  --}}
                <a href="{{url('/request-join-into-sphere/'.$userId.'/'.$subSphere->id)}}">join</a>
              @else
              {{-- if exit, but status joining pending , will show this -> sent your request joining , wait the accepting  on it --}}
                <?php $sphereUserPendingCount=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$subSphere->id,'request_joining_status'=>'pending_status_request_joining'])->count(); ?>
                @if($sphereUserPendingCount!==0)
                  <h5 style="color:red">sent your request joining , wait the accepting  on it</h5>
                  and you can<a href="{{url('/cancel-my-request-joining-into-sphere/'.$user->id.'/'.$subSphere->id)}}">cancel</a>  
                @else
                  {{-- if exit, and status joining not pending : cases-> joining status accepted , invitation status accepted , invitation status pending --}}
                  <?php $sphereUserAcceptedCount=DB::table('sphere_users')->where(['user_id'=>$userId,'sphere_id'=>$subSphere->id,'request_joining_status'=>'accepted_status_request_joining'])->orWhere(['invitation_status'=>'accepted_inivitation_status'])->count(); ?>
                  @if($sphereUserAcceptedCount!==0)
                    {{-- cases-> joining status accepted , invitation status accepted  --}}
                    <a href="{{url('/sphere/'.$subSphere->id.'/tasks')}}">Tasks</a>  
                    <a href="{{url('/sphere/'.$subSphere->id.'/surveys')}}">Surveys</a>  
                    <a href="{{url('/sphere/'.$subSphere->id.'/projects')}}">Projects</a>  
                    <a href="{{url('/sphere/'.$subSphere->id.'/events')}}">Events</a> 
                    <a href="{{url('/sphere/'.$subSphere->id.'/conversations')}}">Conversation</a>  

                  @else
                    {{-- case-> invitation status pending  --}}
                    <h4>you received invitation to join into this sphere , pls check your profile to see it , to be able see this sphere</h4>
                  @endif
                @endif
              @endif
              <div class="sglefeedsec bg01" style="width: 47%;!important;
              margin-left: 636px;!important;">
                <div class="lftimgsec"><img src="{{asset('/images/backend_images/spheres/small/'.$subSphere->image)}}" alt="" /></div>
                <div class="rytimgcontsec">
                  <h2>>{{$subSphere->name}}</h2>
                  <?php
                  $user=DB::table('users')->where(['id'=>$subSphere->founder_id])->first();
                ?>
                <p>Founded by:  {{$user->username}}<br>
                  Established:  {{$subSphere->created_at}}<br>
                  Primary Focus:  {{$subSphere->primary_focus}}<br>
                  </p>
              </div>
              <div style="    width: 47%;
              margin-left: 394px;">

                
                <h2 style="margin-left: 654px;"> tasks from your suggestion spheres</h2>  
                  <?php
                    $tasksSpheresCount=  DB::table('tasks')->where(['sphere_id'=>$subSphere->id])->count();
                    $tasksSpheres=  DB::table('tasks')->where(['sphere_id'=>$subSphere->id])->get();
                  ?>
                  @if($tasksSpheresCount!==0)
                    @foreach($tasksSpheres as $tasksphere)
                    <div class="sglefeedsec bg01" style="width: 47%;!important;
                    margin-left: 636px;!important;">
                      <div class="rytimgcontsec">
                        <p>
                          Name Task:  {{$tasksphere->name_task}}<br>
                          Description  {{$tasksphere->description_task}}<br>
                          status project :  {{$tasksphere->status_task}}<br>
                          From :  {{$tasksphere->start_task}}<br>
                          To :  {{$tasksphere->end_task}}<br>
                        </p>
                    </div>
                  @endforeach
                @endif
              </div>

            <?php
            $projectsSphereCount=  DB::table('projects')->where(['sphere_id'=>$subSphere->id])->count();
            if($projectsSphereCount!==0){
              $projectsSphere=  DB::table('projects')->where(['sphere_id'=>$subSphere->id])->get();
            }
          ?>
            @if($projectsSphereCount!==0)
            <h2 style="margin-left: 654px;"> projects from your suggestion spheres</h2>  
            @foreach($projectsSphere as $projectSphere)
                <div class="sglefeedsec bg01" style="width: 47%;!important;
              margin-left: 636px;!important;">
                  <div class="lftimgsec"><img src="{{asset('/images/backend_images/projects/small/'.$projectSphere->image)}}" alt="" /></div>
                    <h4><a href="{{url('sphere/'.$subSphere->id.'/posts')}}">{{$subSphere->name}}</a></h4> 
                    <p>{{$projectSphere->description}}</p> 
                  </div>
                </div>
            @endforeach
          @endif

          <?php
          $eventsSphereCount=  DB::table('events')->where(['sphere_id'=>$subSphere->id])->count();
          if($eventsSphereCount!==0){
            $eventsSphere=  DB::table('events')->where(['sphere_id'=>$subSphere->id])->get();
          }
          
        ?>
          <div style="    width: 47%;
          margin-left: 393px;">
          @if($eventsSphereCount!==0)

          <h2 style="margin-left: 654px;"> events from your suggestion spheres</h2>  
          @foreach($eventsSphere as $eventsSphere)
            
            <div class="sglefeedsec sglenewsfeedsec" style="width: 47%;!important;
            margin-left: 636px;!important;">
              <div class="rytshape"><span>A</span></div>
              <?php 
                $user=DB::table('users')->where(['id'=>$eventsSphere->user_id])->first();
              ?>
              <div class="lftimgsec"><img src="{{asset('/images/backend_images/user/small/'.$user->image)}}" alt="" /></div>
              <div class="rytimgcontsec">
                <h2><a href="{{url('view-details-event/'.$eventsSphere->sphere_id.'/'.$eventsSphere->id)}}">{{$eventsSphere->title}}</a></h2>
                <p>{{$eventsSphere->description}}</p>
                <p>{{$eventsSphere->event_time}}</p>
                <a href="{{url('/request-join-into-event/'.$user->id.'/'.$eventsSphere->sphere_id.'/'.$eventsSphere->id)}}">Join Event</a> </div>
            </div>
            
          @endforeach
        @endif
      </div>
      @endforeach
      @endif
      @endif
      @endif
      @else

      <span> theres is no suggestions spheres because you not go into the page that it contains on questions , to show here the suggestions sphere basd on your answers , click <a href="/user/answer-on-questions">here</a>  </span><span>to answer on it</span> 
      <a href=""></a>
      @endif
      </div>
    </div>
    </div>
  </div>
</div>
    <style>
    .bodysec{
      background-image:url("{{asset('/images/uploads/First-Page-of-TW.jpg')}}") 
    }
    </style>
@endsection