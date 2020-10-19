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
                <h2>My Assigned Tasks</h2>
                <table>
                  <tbody>
                    
                    @if($my_tasks_assigned_for_me_count!==0)
                      <tr>
                        <th>Sphere</th>
                        <th>Title</th>
                        <th>From</th>
                        <th>To</th>
                      </tr>
                    @foreach($my_tasks_assigned_for_me as $task)
                    <?php 
                        $sphere=DB::table('spheres')->where(['id'=>$task->sphere_id])->first();
                    ?>
                    <tr>
                    
                      <td>
                        <a href="{{url('/sphere/'.$sphere->id.'/posts')}}">{{$sphere->name}}</a>
                      </td>
                      <td>{{$task->name_task}}</td>
                      <td>{{$task->start_task}}</td>
                      <td>{{$task->end_task}}</td>
                    </tr>
                    @endforeach
                    @else
                    <div class="alert alert-info">
                        there is no tasks assigned for you , until now
                    </div>
                    @endif
                  </tbody>
                </table>
              </li>
              <li>
                <h2>Open Tasks in my Spheres</h2>
                <table>
                  <tbody>
                    @if($my_tasks_open_count!==0)
                      <tr>
                        <th>Sphere</th>
                        <th>Title</th>
                        <th>From</th>
                        <th>To</th>
                      </tr>
                    @foreach($my_tasks_open as $task)
                    <?php 
                        $sphere=DB::table('spheres')->where(['id'=>$task->sphere_id])->first();

                    ?>
                    <tr>
                    
                      <td>
                        {{$sphere->name}}
                      </td>
                      <td>{{$task->name_task}}</td>
                      <td>{{$task->start_task}}</td>
                      <td>{{$task->end_task}}</td>
                    </tr>
                    @endforeach
                   
                    @else
                    <div class="alert alert-info">
                        there is no tasks open for you , until now
                    </div>
                    @endif
                  </tbody>
                </table>
              </li>
              <li>
                <h2>Completed Tasks</h2>
                <table>
                  <tbody>
                    @if($my_tasks_close_count!==0)
                      <tr>
                        <th>Sphere</th>
                        <th>Title</th>
                        <th>From</th>
                        <th>To</th>
                      </tr>
                    @foreach($my_tasks_close as $task)
                    <?php 
                        $sphere=DB::table('spheres')->where(['id'=>$task->sphere_id])->first();
                    ?>
                    <tr>
                    
                      <td>
                        <a href="{{url('/sphere/'.$sphere->id.'/posts')}}">{{$sphere->name}}</a>
                      </td>
                      <td>{{$task->name_task}}</td>
                      <td>{{$task->start_task}}</td>
                      <td>{{$task->end_task}}</td>
                    </tr>
                    @endforeach
                    @else
                    <div class="alert alert-info">
                        there is no tasks completed for you , until now
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
   background-image:url("{{asset('/images/backend_images/spheres/small/'.$sphere->cover_image)}}") 
 }
</style>
@endsection
