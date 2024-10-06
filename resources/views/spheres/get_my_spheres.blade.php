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
                <h2>My Spheres That I Created</h2>
                <table>
                  <tbody>
                      
                      @if($mySpheresFoundedCount!==0)
                        
                    @foreach($mySpheresFounded as $mySphereFounded)
                    <div  style="    margin-left: -451px;">
                    
                        <img src="{{asset('/images/backend_images/spheres/small/'.$mySphereFounded->image)}}" alt="" style="width: 52px;
                        margin-right: 42px;">
                        <a href="{{url('/sphere/'.$mySphereFounded->id.'/posts')}}">{{$mySphereFounded->name}}</a>
                    </div>
                    @endforeach
                    @else
                    <div class="alert alert-info">
                        there is no spheres you created it  , until now
                    </div>
                    @endif
                  </tbody>
                </table>
              </li>
              <li>
                <h2>My Spheres That I Joined in it </h2>
                <table>
                  <tbody>
                      
                      @if($mySpheresJoinedCount!==0)
                          
                    <tr>
                        <div  style="    margin-left: -8px;" >
                    @foreach($mySpheresJoined as $mySphereJoined)
                    <?php 
                        $sphere=DB::table('spheres')->where(['id'=>$mySphereJoined->sphere_id])->first();
                    ?>
                            

                                <img src="{{asset('/images/backend_images/spheres/small/'.$sphere->image)}}" alt="" style="width: 52px;
                                margin-right: 12px;    border-radius: 50%;">
                                <a href="{{url('/sphere/'.$sphere->id.'/posts')}}">{{$sphere->name}}</a>
                                @if($mySphereJoined->request_joining_status=='pending_status_request_joining')
                                  <span style="color: red">Pending Your Request</span>
                                @elseif($mySphereJoined->request_joining_status=='accepted_status_request_joining')
                                  <span style="color: green">Accepted Your Request</span>
                                @elseif($mySphereJoined->invitation_status=='pending_inivitation_status')
                                  <span style="color: red">Pending Your Invitation</span>
                                @elseif($mySphereJoined->invitation_status=='accepted_inivitation_status')
                                  <span style="color: green">Accepted Your Invitation</span>
                                @endif
                                @endforeach
                        </div>

                    </tr>
                    @else
                    <div class="alert alert-info">
                        there is no spheres you created it  , until now
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
