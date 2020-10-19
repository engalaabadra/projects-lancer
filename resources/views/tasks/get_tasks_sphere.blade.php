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
                @foreach($mySpheresFounded as $mySphereFounded)
                <li><span>f</span>
                    <a href="{{url('/sphere/'.$mySphereFounded->id.'/posts')}}">{{$mySphereFounded->name}}</a>
                     </li>
                @endforeach
                @foreach($mySpheresJoined as $mySphereJoined)
                  <?php
                        $sphere=DB::table('spheres')->where(['id'=>$mySphereJoined->sphere_id])->first();
                  ?>
                  <li><span>J</span>
                    <a href="{{url('/sphere/'.$sphere->id.'/posts')}}">{{$sphere->name}}</a>
                     </li>
                  @endforeach
              </ul>
              <a href="/user/my-spheres" class="tm-my-spheres-btn">More...</a>
            </div>
          </div>

          <div id="datepicker"></div>

        </div>
       <h3 style="    margin-left: 296px;"><a href="{{url('/sphere/'.$sphere->id.'/posts')}}">{{$sphere->name}}</a></h3> 

        <div class="middle_section column-section">
          <div class="tmmytasksmain-middle-panel">

            <ul>
              <li>
                <h2>Opening Tasks</h2>
                <table>
                  <tbody>
                    
                    @if($tasks_sphere_open_count!==0)
                      <tr>
                        <th>Title</th>
                        <th>From</th>
                        <th>To</th>
                      </tr>
                    @foreach($tasks_sphere_open as $task)
                    <tr>
                      <td>{{$task->name_task}}</td>
                      <td>{{$task->start_task}}</td>
                      <td>{{$task->end_task}}</td>
                    </tr>
                    @endforeach
                    @else
                    <div class="alert alert-info">
                        there is no tasks is opening , until now
                    </div>
                    @endif
                  </tbody>
                </table>
              </li>
              <li>
                <h2>closed Tasks </h2>
                <table>
                  <tbody>
                    @if($tasks_sphere_close_count!==0)
                      <tr>
                        <th>Title</th>
                        <th>From</th>
                        <th>To</th>
                      </tr>
                    @foreach($tasks_sphere_close as $task)
                    <tr>
                      <td>{{$task->name_task}}</td>
                      <td>{{$task->start_task}}</td>
                      <td>{{$task->end_task}}</td>
                    </tr>
                    @endforeach
                   
                    @else
                    <div class="alert alert-info">
                        there is no tasks closed , until now
                    </div>
                    @endif
                  </tbody>
                </table>
              </li>
              <li>
              </li>
            </ul>
          </div>
        </div>
        <div class="right_task">
            <div class="oval_navigation">
                <a href="#" class="round1">
                  <span>Surveys</span>
                </a>
                <a href="{{url('/sphere/'.$sphere->id.'/events')}}" class="round2">
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
                      <img src="{{asset('/images/backend_images/spheres/small/'.$sphere->image)}}" alt="" style="   border-radius: 50%;  margin-top: 85px;">
                      Join
                    <br />
                    <span>The Sphere</span><br />Conversations
                  </div>
                </a>
              </div>

        </div>
      </div>
    </div>
  </div>
  <style>
     .bodysec{
   background-image:url("{{asset('/images/backend_images/spheres/small/'.$sphere->cover_image)}}") 
 }
</style>
@endsection
