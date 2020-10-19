
@extends ("layout")
@section("content")
@if($sphereThisProjectsCount!==0)
  <form action="{{url('/main-page-sphere/add-vote-on-project-in-sphere/'.$sphereThisProjects->id)}}" method="post">{{csrf_field()}}
    <select name="vote_project" id="" class="form-control">projects :
  @foreach($projectsSphere as $projectSphere)
    <option value="{{$projectSphere->id}}"> {{$projectSphere->name}}</option>
  @endforeach
    </select>
    <button type="submit">Add My Vote</button>
  </form>
@endif
@endsection
