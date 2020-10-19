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
                <h2>My Surveys</h2>
                <table>
                  <tbody>
                    
                    @if($surveysJoinedItCount!==0)
                      <tr>
                        <th>Sphere</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>created_at</th>
                      </tr>
                    @foreach($surveysJoinedIt as $surveyJoinedIt)
                    <?php 
                        $sphere=DB::table('spheres')->where(['id'=>$surveyJoinedIt->sphere_id])->first();
                        $survey=DB::table('surveys')->where(['id'=>$surveyJoinedIt->survey_id])->first();
                    ?>
                    <tr>
                    
                      <td>
                        <a href="{{url('/sphere/'.$sphere->id.'/posts')}}">{{$sphere->name}}</a>
                      </td>
                      <td> 
                        <a href="{{url('/sphere/'.$sphere->id.'/posts')}}"></a>
                        {{$survey->title}}</td>
                      <td>{{$survey->description}}</td>
                      <td>{{$survey->created_at}}</td>
                    </tr>
                    @endforeach
                    @else
                    <div class="alert alert-info">
                        there is no survyes  for you , until now
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
