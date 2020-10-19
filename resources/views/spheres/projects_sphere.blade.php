


@extends ("layout")
@section("content")
<?php
use App\Sphere;
use App\Project;
use App\Comment;
use App\Task;
use App\User;
?>

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
      </div>
      <?php 
      $user=DB::table('users')->where(['email'=>Session::get('sessionUser')])->first();
      ?>
     <h3 style="    margin-left: 296px;"><a href="{{url('/sphere/'.$sphere->id.'/posts')}}">{{$sphere->name}}</a></h3>


      <div class="middle_section column-section">
        <div class="tmmytasksmain-middle-panel">

          <ul>
            <li>
              <h2>projects opening</h2>
              <table>
                <tbody>
                  
                  @if($getProjectsSphereInProgressCount!==0)
                    <tr>
                      <th>Title</th>
                      <th>From</th>
                      <th>To</th>
                    </tr>
                  @foreach($getProjectsSphereInProgress as $project)
                  <?php 
                    $projectCompletedCount=DB::table('tasks')->where(['project_id'=>$project->id,'sphere_id'=>$sphereId,'status_task'=>'completed'])->count();
                    $projectInProgressCount=DB::table('tasks')->where(['project_id'=>$project->id,'sphere_id'=>$sphereId,'status_task'=>'in-progress'])->count();
                 ?>
                  @if($projectCompletedCount!==0)
                  <?php 

                  DB::table('projects')->where(['id'=>$project->id])->update(['status_project'=>'completed']);
                  ?>
                  @endif
                  @if($projectInProgressCount!==0)
                  <?php 

                  DB::table('projects')->where(['id'=>$project->id])->update(['status_project'=>'in-progress']);
                  ?>
                  @endif
                    <?php 
                    $countProjectsSphereActivatedVote=  DB::table('projects')->where(['sphere_id'=>$sphereId,'status_vote_project'=>'activated_vote'])->count();

                    ?>
                    @if($countProjectsSphereActivatedVote!==0)
                  <a href="{{url('/main-page-sphere/projects-in-sphere-to-votes/'.$sphereId)}}">Add Vote</a>
                  @else
                    <div class="alert alert-info">
                      Not Available Votes in this project , because this project in sphere not contains on the enough users , so when this sphere contains on the enough users , this project become available to vote on it
                    </div>
                  @endif
                  <a href="{{url('/sphere/'.$sphereId.'/project/'.$project->id.'/tasks')}}">get tasks this project</a>
                  <tr>
                    <td><a href="{{url('/view-details-project/'.$sphereId.'/'.$project->id)}}">{{$project->name}}</a></td>
                    <td>{{$project->start_project}}</td>
                    <td>{{$project->end_project}}</td>
                    @if($project->status_project=='completed')
                      <h5 style="color:green">{{$project->status_project}}</h5>
                    @elseif($project->status_project=='in-progress')
                      <h5 style="color:red">{{$project->status_project}}</h5>
                    @endif
                  </tr>
                  @endforeach
                  @else
                  <div class="alert alert-info">
                      there is no projects opening , until now
                  </div>
                  @endif
                </tbody>
              </table>
            </li>
            <li>
              <h2>projects completed</h2>
              <table>
                <tbody>
                  @if($getProjectsSphereCompletedCount!==0)
                    <tr>
                      <th>Title</th>
                      <th>From</th>
                      <th>To</th>
                    </tr>
                  @foreach($getProjectsSphereCompleted as $project)
                  <?php 
                  $countProjectsSphereActivatedVote=  DB::table('projects')->where(['sphere_id'=>$sphereId,'status_vote_project'=>'activated_vote'])->count();

                  ?>
                  @if($countProjectsSphereActivatedVote!==0)
                <a href="{{url('/main-page-sphere/projects-in-sphere-to-votes/'.$sphereId)}}">Add Vote</a>
                @else
                  <div class="alert alert-info">
                    Not Available Votes in this project , because this project in sphere not contains on the enough users , so when this sphere contains on the enough users , this project become available to vote on it
                  </div>
                @endif
                  <a href="{{url('/sphere/'.$sphereId.'/project/'.$project->id.'/tasks')}}">get tasks this project</a>
                  <tr>
                    <td><a href="{{url('/view-details-project/'.$sphereId.'/'.$project->id)}}">{{$project->name}}</a></td>
                    <td>{{$project->start_project}}</td>
                    <td>{{$project->end_project}}</td>
                  </tr>
                  @endforeach
                  @else
                  <div class="alert alert-info">
                      there is no projects completed , until now
                  </div>
                  @endif
                </tbody>
              </table>
            </li>
            <li>
              <h2>projects that i am apart in it</h2>
              <table>
                <tbody>
                  @if($getProjectsSphereIamInItCount!==0)
                    <tr>
                      <th>Title</th>
                      <th>From</th>
                      <th>To</th>
                    </tr>
                  @foreach($getProjectsSphereIamInIt as $getProjectSphereIamInIt)
                  <?php 
                    $project=DB::table('projects')->where(['id'=>$getProjectSphereIamInIt->project_id])->first();
                  ?>
                    <?php 
                    $countProjectsSphereActivatedVote=  DB::table('projects')->where(['sphere_id'=>$sphereId,'status_vote_project'=>'activated_vote'])->count();

                    ?>
                    @if($countProjectsSphereActivatedVote!==0)
                  <a href="{{url('/main-page-sphere/projects-in-sphere-to-votes/'.$sphereId)}}">Add Vote</a>
                  @else
                    <div class="alert alert-info">
                      Not Available Votes in this project , because this project in sphere not contains on the enough users , so when this sphere contains on the enough users , this project become available to vote on it
                    </div>
                  @endif
                  <a href="{{url('/sphere/'.$sphereId.'/project/'.$project->id.'/tasks')}}">get tasks this project</a>
                  <tr>
                    <td><a href="{{url('/view-details-project/'.$sphereId.'/'.$project->id)}}">{{$project->name}}</a></td>
                    <td>{{$project->start_project}}</td>
                    <td>{{$project->end_project}}</td>
                  </tr>
                  @endforeach
                  @else
                  <div class="alert alert-info">
                      there is no projects i am in it , until now
                  </div>
                  @endif
                </tbody>
              </table>
            </li>
          </ul>
        </div>
      </div>
      <div class="right_task">
          <div class="oval_navigation">
              <a href="{{url('/sphere/'.$sphere->id.'/surveys')}}" class="round1">
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

@endsection
