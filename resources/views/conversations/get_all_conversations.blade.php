@extends ("layout")
@section("content")
<div class="bodysec">
    <div class="container-fluid">
      <div class="row">
        <div class="leftpanel">
          <?php 
          $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
      ?>

          <h1>Sphere
            <a href="{{url('sphere/'.$sphere->id.'/posts')}}">{{$sphere->name}}</a>  
            Conversations </h1>
            <div><img src="{{asset('/images/backend_images/spheres/small/'.$sphere->image)}}" alt="" /></div>
            <?php 
              $conversationsSphereCount=DB::table('conversations')->where(['sphere_id'=>$sphere->id])->count();
            ?>
            <div>
              <a href="{{url('/user/add-new-conversation/sphere/'.$sphere->id.'/user/'.$userId)}}" class="newtopicbut"> New Conversation</a></div>
                @if($conversationsCount!==0)
                <a href="{{url('/user/add-new-topic/specific-conversation/sphere/'.$sphere->id.'/user/'.$userId)}}" class="newtopicbut">New Topic</a></div>

            </div>
            
        <div class="rightpanel">
          <div class="box">
            <div class="desktopview">
              <div class="sglelisttitlesec">
                <div class="row">
                  <div class="imagesec">&nbsp;</div>
                  <div class="topictxt">topic</div>
                  <div class="iconsec">&nbsp;</div>
                  <div class="replytxt">Replies</div>
                  <div class="projecttxt">Projects</div>
                  <div class="activitytxt">Viewed</div>
                </div>
              </div>
              @foreach($conversations as $conversation)
              <div class="sglelistsec">
                <div class="row items-container">
                  <div class="imagesec item">
                    <div class="verticalalign">
                      <div class="imgthumbnail"><img src="{{asset('/images/backend_images/spheres/small/'.$sphere->image)}}" alt=""></div>
                    </div>
                  </div>
                  <div class="topictxt item">
                    <div class="verticalalign">
                        <?php
                        $users=DB::table('conversations_users')->where(['conversation_id'=>$conversation->conversation_id])->get();
                        ?>
                        @foreach($users as $user)
                        <?php
                            $user=DB::table('users')->where(['id'=>$user->id])->first();
                        ?>
                      user:  {{$user->email}}
                        @endforeach
                      <p>Title:
                        <a href="{{url('/view-conversation/'.$conversation->id.'/sphere/'.$sphere->id)}}">{{$conversation->title}}</a>  
                        </p>
                      <p>Description:   {{$conversation->description}}</p>

                      
                    </div>
                </div>
                <div class="iconsec item">
                    <div class="verticalalign">&nbsp;</div>
                </div>
                <?php 
        $numReplies=DB::table('replies')->where(['conversation_id'=>$conversation->id,'sphere_id'=>$sphere->id])->count();
        $numTopics=DB::table('topics')->where(['conversation_id'=>$conversation->id,'sphere_id'=>$sphere->id])->count();
        $projectsCountConversation=DB::table('conversations_projects')->where(['conversation_id'=>$conversation->id])->count();
        $numViews=DB::table('views')->where(['conversation_id'=>$conversation->id,'sphere_id'=>$sphere->id])->count();

                ?>
                <div class="replytxt item">
                  <div class="verticalalign">{{$numTopics}}</div>
              </div>
                <div class="replytxt item">
                    <div class="verticalalign">{{$numReplies}}</div>
                </div>
                <div class="projecttxt item">

                                        <div class="verticalalign">{{$projectsCountConversation}}</div>
                  </div>
                  <div class="activitytxt item">
                    
                      <div class="verticalalign">{{$numViews}}</div>
                    </div>
                </div>
            </div>
            @endforeach
              
            </div>
          </div>
          @else
            <div class="alert alert-info">
                there is no conversations until now in this sphere
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  <style>
    .bodysec{
   background-image:url("{{asset('/images/backend_images/spheres/small/'.$sphere->cover_image)}}") !important; 
 }
</style>
@endsection
