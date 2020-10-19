@extends ("layout")
@section("content")
<?php
use App\Project;
use App\Sphere;
?>
@if($spheresCreatedByThisUserCount!==0)
   @foreach($spheresCreatedByThisUser as $sphere)
      <div class="card">
         <div class="card-header">
            <a href="{{url('/main-page-sphere/all-data-sphere/'.$sphere->id)}}">{{$sphere->image}}</a>
         </div>
         <div class="card-body">
            {{$sphere->description}}
         </div>
         <?php
         $projectsSphere=  Project::where(['sphere_id'=>$sphere->id])->get();
         $projectsSphereCount=  Project::where(['sphere_id'=>$sphere->id])->count();
         ?>
         @if($projectsSphereCount)
         @foreach($projectsSphere as $project)
            <h5>{{$project->name}}</h5>
         @endforeach
         @else
            <h6>there no projects until now</h6>
            <a href="{{url('/add-project/'.$sphere->id)}}">add project</a>
         @endif
      </div>
   @endforeach  
@else
      <div class="alert alert-info">
         there is no spheres you created it
      </div>

@endif

@endsection
