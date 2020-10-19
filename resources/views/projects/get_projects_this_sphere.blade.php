


@extends ("layout")
@section("content")
<?php
  use App\Sphere;
  use App\Comment;
  use App\Task;
  use App\User;
?>
<h1>sphere this projects : {{$nameSphereThisProjects}}</h1>
<a href="url('/get-project-this-sphere/'.$sphereThisProjects->id)"></a>
<h3>projects : {{$countProjectsSphere}} </h3>
<h3>countTasksSphere : {{$countTasksSphere}} </h3>
<h3>countSurveysSphere : {{$countSurveysSphere}} </h3>
<h3>countConversationsSphere : {{$countConversationsSphere}} </h3>
<h3>countEventsSphere : {{$countEventsSphere}} </h3>
<h3>countUsersSphere : {{$countUsersSphere}} </h3>
<h3>countLeadrsSphere : {{$countLeadersSphere}} </h3>
<form action="{{url('/all-data-sphere/'.$sphereThisProjects->id)}}" method="post"style="margin-top: 69px;margin-left: 122px;"enctype="multipart/form-data">{{csrf_field()}}
  <div class="form-group">
    <label for="title"class="form-label">title</label>
    <input name="title" class="form-control" id="title" placeholder="title">
  </div>

  <div class="form-group">
    <label for="description"class="form-label">description</label>
    <input name="description" class="form-control" id="description" placeholder="description">
  </div>
  <button name="submit" class="btn btn-primary"style=" margin-left: 492px;">create</button>
</form>
<div class="widget-content nopadding">
  <h1>sphere this projects : {{$nameSphereThisProjects}}</h1>
  <h1>desc sphere this projects : {{$sphereThisProjects->description}}</h1>
  <h3>projects this sphere</h3>
  @foreach($getProjectsSphere as $project)
    <div class="card">
        <div class="card-header">
          <a href="{{url('/view-details-project/'.$project->id)}}">{{$project->image}}</a>
        </div>
        <div class="card-body">
          {{$project->description}}
        </div>
        <div class="card-footer">{{$project->name}}</div>
        </div>
            <?php
            $tasksThisProject=Task::where(['project_id'=>$project->id])->get();
            ?>
            @foreach($tasksThisProject as $taskThisProject)
              <h6>tasks this project : {{$taskThisProject->title}}</h6>
            @endforeach 


@endforeach


</div>

<h5>posts this sphere</h5>
@foreach($getPostsSphere as $post)
  <div class="card">
     <div class="card-header">
      <?php
        $user= User::where(['id'=>$post->user_id])->first();
      ?>
    <div>
    {{$user->image}}
  </div>
     <a href="{{url('/view-details-post/'.$post->id)}}">Name:{{$user->username}}</a>
        <h5>JobTitle:{{$user->job_title}}</h5>
  </div>
     <div class="card-body">
     {{$post->body}}
     </div>
     <div class="card-footer">{{$post->created_at}}</div>
     </div>
      <?php $getCommentsSphere=Comment::where(['sphere_id'=>$sphereThisProjects->id,'post_id'=>$post->id])->get(); ?>
      @foreach($getCommentsSphere as $comment)
        {{$comment->title}}
      @endforeach
@endforeach
</div>
@endsection

