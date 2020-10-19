@extends ("layout")
@section("content")
<div class="bodysec survey">
    <div class="survey-details-back">
      <div class="main_back">
        <div class="left_task">
          <div class="task-left-head">
            <div class="tmmytasksleft-heading">
              <h2>My Spheres</h2>
            </div>
            <div class="tm-my-spheres">
              <ul>
                @if($mySpheresFoundedCount!==0)
                  @foreach($mySpheresFounded as $mySphereFounded)
                  <li><span>f</span>
                    <a href="{{url('/sphere/'.$mySphereFounded->id.'/posts')}}">{{$mySphereFounded->name}}</a>
                  </li>
                  @endforeach
                @else
                  <li>
                    Not Exist
                  </li>
                @endif
                @if($mySpheresJoinedCount!==0)
                  @foreach($mySpheresJoined as $mySphereJoined)
                    <?php
                      $sphere=DB::table('spheres')->where(['id'=>$mySphereJoined->sphere_id])->first();
                    ?>
                    <li><span>J</span>
                      <a href="{{url('/sphere/'.$sphere->id.'/posts')}}">{{$sphere->name}}</a>
                    </li>
                  @endforeach
                @else
                  <li>
                    Not Exist
                  </li>
                @endif
              </ul>
              <a href="/user/my-spheres" class="tm-my-spheres-btn">More...</a>
            </div>
          </div>

          <div id="datepicker"></div>

        </div>
        <div class="middle_section column-section">
          <div class="tmmytasksmain-middle-panel">
            <ul>
              <li>


                <h2>My Projects That I Created </h2>
                <table>
                  <tbody>
                      
                      @if($myProjectsCreatedCount!==0)
                          
                    <tr>
                      <div  style="    margin-left: -8px;" >
                        @foreach($myProjectsCreated as $myProjectsCreated)
                          <img src="{{asset('/images/backend_images/projects/small/'.$myProjectsCreated->image)}}" alt="" style="width: 52px;
                          margin-right: 12px;    border-radius: 50%;">
                          <a href="{{url('/sphere/'.$myProjectsCreated->id.'/posts')}}">{{$myProjectsCreated->name}}</a>
                        @endforeach
                        </div>
                    </tr>
                    @else
                    <div class="alert alert-info">
                        there is no projects you created it  , until now
                    </div>
                    @endif
                  </tbody>
                </table>

              </li>
              <li>
                <h2>My Projects That I Joined in it </h2>
                <table>
                  <tbody>
                      
                      @if($myProjectsJoinedCount!==0)
                          
                    <tr>
                        <div  style="    margin-left: -8px;" >
                    @foreach($myProjectsJoined as $myProjectsJoined)
                    <?php 
                        $project=DB::table('projects')->where(['id'=>$myProjectsJoined->project_id])->first();
                    ?>
                            

                                <img src="{{asset('/images/backend_images/projects/small/'.$project->image)}}" alt="" style="width: 52px;
                                margin-right: 12px;    border-radius: 50%;">
                                <a href="{{url('view-details-project/'.$myProjectsJoined->sphere_id.'/'.$myProjectsJoined->project_id)}}">{{$project->name}}</a>
                                @if($myProjectsJoined->request_joining_status=='pending_status_request_joining')
                                <span style="color: red">Pending Your Request</span>
                              @elseif($myProjectsJoined->request_joining_status=='accepted_status_request_joining')
                              <span style="color: green">Accepted Your Request</span>
                              @elseif($myProjectsJoined->invitation_status=='pending_inivitation_status')
                              <span style="color: red">Pending Your Invitation</span>
                              @elseif($myProjectsJoined->invitation_status=='accepted_inivitation_status')
                              <span style="color: green">Accepted Your Invitation</span>
                              @endif
                                @endforeach
                        </div>

                    </tr>
                    @else
                    <div class="alert alert-info">
                        there is no projects you joined it  , until now
                    </div>
                    @endif
                  </tbody>
                </table>
              </li>
              
            </ul>
          </div>
        </div>

      </div>
    </div>
  </div>
  <style>
    .bodysec{
  background-image:url("{{asset('/images/backend_images/user/small/'.$user->cover_image)}}") 
}
</style>
@endsection
