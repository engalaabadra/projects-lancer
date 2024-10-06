


@extends ("layout")
@section("content")
<?php
    use App\Sphere;
?>
<div class="widget-content nopadding">
<table id="table_id" class="table table-bordered data-table">
    <thead>
        <tr>
            <th>project id</th>
            <th>sphere of this project</th>
            <th>title</th>
            <th>description</th>
            <th>image</th>
            <th>actions</th>
            
         
        </tr>
    </thead>
    <tbody>
@foreach($getAllProjects as $project)

        <tr class="gradeX">

            <td>{{$project->id}}</td>
            <?php
                $sphereThisProject=Sphere::where(['id'=>$project->sphere_id])->first();
            
            ?>
          <td>{{$sphereThisProject->title}}</td>  
            <td>{{$project->name}}</td>
  
            <td>{{$project->image}}</td>
          <td>
          
         <a target="_blank" href="{{url('/view-details-project/'.$project->id)}}">view project details</a><br>
         <a target="_blank" href="{{url('delete-cat/'.$project->id)}}">view order invoice</a>
          </td>
 
        </tr>
    </tbody>
@endforeach


</div>
@endsection