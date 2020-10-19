

@extends('layout')


@section('content')

<div class="innerbodysec formobheight">
  <a href="/user/my-tasks">My Tasks</a>
  <a href="{{url('/user/get-all-invitations/'.$userId)}}">view all invitations</a>
  <a href="{{url('/user/edit-profile/'.$userId)}}" class="btn btn-info">edit profile</a>
  <a href="{{url('/user/change-image/'.$userId)}}"class="btn btn-info">change my image profile</a>
  <a href="{{url('/user/change-cover-image/'.$userId)}}"class="btn btn-info">change my cover image profile</a>
  <div class="leftbiopanel">
    <div class="box">
        @if(empty($user->image))

        <div><img src="https://getdrawings.com/free-icon/default-avatar-icon-65.png" alt="Avatar" class="avatar avatar-user width-full border bg-white" style="    width: 205px;
height: 156px;
border-radius: 29%;"></div>

        @else
        <div><img src="{{asset('/images/backend_images/user/small/'.$user->image)}}" alt="" style="    border-radius: 50%;"/></div>

@endif
        Username : {{$user->username}}
        <hr>
      Bio : {{$user->bio}}
      <hr>
      Interstes:   {{$user->interests}}
      <hr>
    Best Sentence:    {{$user->best_sentence}}
      </div>
    </div>
    <div class="rightbiopanel">
      <div class="midbox">
        <div class="sglebiosec">
          <p>{{$user->best_sentence}}</p>
        </div>
        <div class="sglebiosec">
          <div class="row items-container">
            <div class="halfcolumn item">
              <h2>Founded</h2>
                @if($spheresFoundedCount==0)
                    <div class="alert alert-info">
                    there is no spheres you founded it , until now
                    </div>
                @else
                <div class="row">
                  @foreach($spheresFounded as $sphereFounded)
                        <div class="sgleiconcontbio"><img src="{{asset('/images/backend_images/spheres/small/'.$sphereFounded->image)}}" />
                        <h3><a href="{{url('/sphere/'.$sphereFounded->id.'/posts')}}">{{$sphereFounded->name}}</a></h3>
                        </div>
                        @endforeach
                    </div>
                @endif

                
            </div>


            <div class="halfcolumn item">
              <h2>Joined</h2>
              @if($spheresJoinedCount==0)
              <div class="alert alert-info">
                there is no spheres you joined in it , until now
                </div>
            @else
              <div class="row">
            @foreach($spheresJoined as $sphereJoined)
                <?php
                    $sphere=DB::table('spheres')->where(['id'=>$sphereJoined->sphere_id])->first();

                ?>
                <div class="sgleiconcontbio"><img src="{{asset('/images/backend_images/spheres/small/'.$sphere->image)}}" />
                  <h3><a href="{{url('/sphere/'.$sphereJoined->id.'/posts')}}">{{$sphere->name}}</a></h3>
                </div>
                
                @endforeach
              </div>
            @endif
            </div>
          </div>
        </div>
        <div style="width:100%; overflow:hidden;">
          <div class="row items-container">
            <div class="btmfirstsec item">
              <div class="sglebiosec">
                <h2>Projects</h2>
                  </tbody>
                <table  class="table-bordered">
                  <thead>
                  <tr>
                    <th scope="col" class="text-center">Created</th>
                    <th scope="col" class="text-center" >Joined</th>
                  </tr>
                </thead>
                <tbody>
                  
                  <tr>
                    @if($projectsJoinedCount==0)
                <td class="alert alert-info">
                there is no projects you joined in it , until now
                </td>
            @else

                    <td>
                @foreach($projectsJoined as $projectJoined)
                <?php
                    $project=DB::table('projects')->where(['id'=>$projectJoined->project_id])->first();

                ?>
                      <a href="{{url('view-details-project/'.$projectJoined->sphere_id.'/'.$project->id)}}">{{$project->name}}</a>
                      @endforeach
                      </td>
            @endif
            @if($projectsFoundedCount==0)
                      <tr>

                        <td class="alert alert-info">
                        there is no projects you founded it , until now
                        </td>
                      </tr>
                @else
                    @foreach($projectsFounded as $projectFounded)
                    <tr>

                      <td>
                        <a href="{{url('view-details-project/'.$projectFounded->sphere_id.'/'.$projectFounded->id)}}">jhjjhhj{{$projectFounded->name}}</a>
                      </td>
                    </tr>
                    @endforeach
                @endif
                    </tbody>

                </table>
              </div>
            </div>
            <div class="btmmidsec item">
              <div class="sglebiosec">
                <h2>Surveys</h2>
                <p>List surveys use has done</p>
                <a href="/get-all-surveys-joined-it">See More</a> </div>
            </div>
            
            <div class="btmrytsec item">
              <h2>Photos </h2>

                <div class="row sglebiosec" style="height: 74%;">
                  @foreach($imagesUser as $userImage)
                  <div class="col-md-3">
                    @if(empty($userImage->image))
  
                    <img src="https://getdrawings.com/free-icon/default-avatar-icon-65.png" alt="Avatar" class="avatar avatar-user width-full border bg-white" >
                    @else
                    <img src="{{asset('/images/backend_images/user/small/'.$userImage->image)}}" alt="" />
                    @endif
                  </div>
                  @endforeach
                  </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    My followers
    <?php
      $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
      $myFollowers=DB::table('users_followers')->where(['user_id'=>$user->id,'status_following'=>'accepted_status'])->get();
      $myFollowersCount=DB::table('users_followers')->where(['user_id'=>$user->id,'status_following'=>'accepted_status'])->count();
    
    ?>
    @if($myFollowersCount!==0)
      @foreach($myFollowers as $myFollower)
      <?php
        $userFollower=DB::table('users')->where(['id'=>$myFollower->follower_id])->first();
      ?>
      @if($userFollower->id!==$user->id)
        Username: <a href="{{url('/user/view-profile-member/'.$userFollower->email)}}" class="btn btn-success">username: {{$userFollower->email}}</a>
        <a href="{{url('/show-conversation-me/'.$user->id.'/member/'.$myFollower->follower_id)}}" class="btn btn-info">message</a>
      @endif
      <?php
        $roomUsers=  DB::table('messages')->where(['user_id'=>$myFollower->user_id,'member_id'=>$myFollower->follower_id])->first();
        $roomUsersCount=  DB::table('messages')->where(['user_id'=>$myFollower->user_id,'member_id'=>$myFollower->follower_id])->count();
    
      ?>
      @if($roomUsersCount!==0)
      <?php   $roomId=$roomUsers->id; ?>
      @endif
    
      @endforeach
    @else
    
        there is no followers for you  until now
    @endif
    
    
    Persons that i am following them:
    <?php
      $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
      $personsIFollowThem=DB::table('users_followers')->where(['follower_id'=>$user->id,'status_following'=>'accepted_status'])->get();
      $personsIFollowThemCount=DB::table('users_followers')->where(['follower_id'=>$user->id,'status_following'=>'accepted_status'])->count();
    
    ?>
    @if($personsIFollowThemCount!==0)
    @foreach($personsIFollowThem as $personIFollowThem)
    
    <?php
      $person=DB::table('users')->where(['id'=>$personIFollowThem->user_id])->first();

    ?>
      @if($person->id!==$user->id)

      Username: <a href="{{url('/user/view-profile-member/'.$person->email)}}" class="btn btn-success">username: {{$person->email}}</a>
    
      <a href="{{url('/show-conversation-me/'.$user->id.'/member/'.$personIFollowThem->user_id)}}" class="btn btn-info">message</a>
      @endif
      @endforeach
    @else
    
      there is no followers for you  until now
    @endif
    </div>
  </div>
<style>
  .formobheight{
 background-image:url("{{asset('/images/backend_images/user/small/'.$userCoverImage)}}") 
}
</style>

@endsection

