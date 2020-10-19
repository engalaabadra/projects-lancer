@extends ("layout")
@section("content")
<div class="innerbodybg arena_indi">
  
  <div class="bodysec survey">
    
    <div class="survey-details-back">
      <div class="main_back">
        <div style=" overflow-y: scroll; height: 80rem ">
          <div style="    width: 50%;
          margin-bottom: 36px;">
            <div id="app">
            <Conversation  conversation_id={{$conversationId}} sphere_id={{$sphere_id}} user_id={{$userId}} > </Conversation>
            </div>
          </div>
        </div>
                  <div class="left_task arena_right">
                    <div class="alignpad">
                      <div align="center"><a href="{{url('/user/add-new-topic/conversation/'.$conversationId.'/sphere/'.$sphere_id.'/user/'.$userId)}}" class="newtopicbut">New Topic</a><a href="#" class="bellicon"><img src="images/bell2.png" alt=""></a></div>
                     @if($theLastTopicCount!==0)
                     <?php 
                    $lastTopic=DB::table('topics')->where(['id'=>$theLastTopic->id])->first();
                    ?>
                      <a href="{{url('/user/add-project/conversation/'.$conversationId.'/sphere/'.$sphere_id.'/user/'.$userId.'/topic/'.$theLastTopic->id)}}" class="projectbut">Project</a></div>
                    @endif
                  
                    
                  </div>
                  <?php 

                  $conv=DB::table('conversations')->where(['id'=>$conversation->id])->first();
                  ?>
                    <div class="left">
                      <div class="innerleftpart arena_left">
                        <div class="alignpad">
                          <div class="namespheretxt"><img src="{{asset('/images/backend_images/spheres/small/'.$sphere->image)}}" alt=""><span>Name of Sphere Conversation: <a href="{{url('sphere/'.$sphere->id.'/posts')}}">{{$sphere->name}}</a></span></div>
                          <h1>Subject: {{$conv->title}}</h1>
                          <h2>Sub title</h2>
                          <p>{{$conv->description}}</p>
                          <?php 
                            $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
                          ?>
                          @if($newTopicTheLastCount!==0)
                          @if($usernewTopicTheLast->email!==$user->email)
                          <div class="usernametxt"><img src="images/img.png" alt=""><span>Name of user: <a href="{{url('user/view-profile-member/'.$usernewTopicTheLast->email)}}">{{$usernewTopicTheLast->username}}</a><br>
                            who started this topic</span></div>
                          @else
                          <div class="usernametxt"><img src="images/img.png" alt=""><span>Name of user: <a href="{{url('user/view-profile/'.$usernewTopicTheLast->email)}}">{{$usernewTopicTheLast->username}}</a><br>
                            who started this topic</span></div>
                          @endif
                          @endif
                          <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tabletxt">
                            <tr>
                              <td>Created<br>
                                @if($conversationCount!==0)
                                {{$projectsCountConversation}}</td>
                                @else
                                0
                                @endif
                              <td>Replies<br>
                                @if($numReplies!==0)
                                {{$numReplies}}</td>
                                @else
                                0
                                @endif
                                <td>Viewed<br>
                                @if($numViews!==0)
                                {{$numViews}}</td>
                                @else
                                0
                                @endif

                            </tr>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="middle_section column-section">
                      <div class="innermiddlepart">
                        <div class="alignpad">
                          <div class="box">
                            @if($newTopicTheLastCount)
                              <?php
                                $user=DB::table('users')->where(['id'=>$newTopicTheLast->user_id])->first();
                              ?>
                              @if($newTopicTheLast->status_topic=='open')
                                @if($newTopicTheLastCount!==0)
                                  created new topic in this conversation : {{$newTopicTheLast->name}} by <a href="{{url('/view-profile/'.$user->email)}}">{{$user->username}}</a> 
                                @else
                                    <div class="alert alert-info">
                                        there is no topic now in this conversation , you can create new topic
                                    </div>
                                @endif
                                @if($newTopicTheLast->status_topic=='open')

                                        if you want close this topic , you can do it from <a href="{{url('user/close-topic/'.$newTopicTheLast->id)}}">here</a>
                                @endif
                                @if($newProjectTheLastCount!==0)
                                  <?php 

                                $projectTopic=DB::table('projects')->where(['topic_id'=>$newTopicTheLast->id])->first();
                                $projectTopicCount=DB::table('projects')->where(['topic_id'=>$newTopicTheLast->id])->count();
                                ?>
                                  @if($projectTopicCount!==0)
                                    Created new project 
                                    <a href="{{url('/view-details-project/'.$sphere_id.'/'.$projectTopic->id)}}">{{$projectTopic->name}}</a>
                                     based on this topic
                                  @endif
                                @endif

                              @else 
                                    <?php 
                                          $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
                                    ?>
                                      The Topic :   {{$newTopicTheLast->name}} closed by : {{$user->username}}
                              @endif
                            @endif

                          </div>
                        </div>
                      </div>
                    </div>

                   
                </div>
            </div>
        </div>
        <style>
          .main_back{
        background-image:url("{{asset('/images/backend_images/spheres/small/'.$sphere->cover_image)}}") 
        }
        </style>
@endsection
