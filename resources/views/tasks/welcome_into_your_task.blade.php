@extends ("layout")
@section("content")
<?php
    use App\Sphere;
    use App\Comment;
    use App\Task;
    use App\User;

?>
@if(Session::has('flash_message_error'))
    <div class="alert alert-error alert-block">
        <strong>{!!session('flash_message_error')!!}</strong>
    </div>
@endif

@if(Session::has('flash_message_success'))
    <div class="alert alert-success alert-block">
        <strong>{!!session('flash_message_success')!!}</strong>
    </div>
@endif
thank you for your accepting , you can see all your task in list your task
<a href="{{url('/view-details-my-task/'.$task->id.'/'.$sphere_id.'/'.$project_id.'/'.$user_id.'/'.$member_id)}}">Your task </a>

@endsection
